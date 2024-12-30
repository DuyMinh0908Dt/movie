<?php


use Core\Attributes\Route;

function pattern_to_regex($pattern)
{

    $rs = preg_replace('/{([^}]+)}/', '([^/]+)', $pattern);
    $rs = str_replace('/', '\/', $rs);
    return "/$rs/";
}
class ControllerMap
{
    public $actions = [];
    public function __construct(
        public string $path,
        public string $controllerClassName,
        public string $requirePath
    ) {
    }

    public function match(string $uri): bool
    {
        $isMatchController = str_starts_with($uri, $this->path);
        if (!$isMatchController) {
            return false;
        }

        require_once $this->requirePath;
        if (empty($this->actions)) {
            $this->loadActions();
        }
        $actionUri = str_replace($this->path, "", $uri);
        $isMatch = false;
        foreach ($this->actions as $action) {
            $match_result = $action->match($actionUri);
            if (!$match_result) {
                continue;
            }
            $isMatch = true;
            // create instance of controller
            $controller = new $this->controllerClassName();
            $action_name = $match_result[0];
            $path_params = $match_result[1];



            $controller->$action_name(...$path_params);
            break;
        }
        return $isMatch;
    }

    private function loadActions()
    {
        $reflection = new \ReflectionClass($this->controllerClassName);
        foreach ($reflection->getMethods() as $method) {
            if ($method->getAttributes(Route::class)) {
                $attribute = $method->getAttributes(Route::class)[0];
                $arguments = $attribute->getArguments();
                $path = $arguments[0];
                $http_method = $arguments[1];
                $this->actions[] = new AcctionMap(
                    path: $path,
                    method: $http_method,
                    actionName: $method->getName()
                );
            }
        }
        usort($this->actions, function ($a, $b) {
            if (strlen($a->path) === strlen($b->path)) {
                return strcmp($a->path, $b->path);
            }
            return strlen($a->path) > strlen($b->path) ? -1 : 1;
        });
    }

    public static function __set_state(array $data): self
    {
        return new self($data["path"], $data["controllerClassName"], $data["requirePath"]);
    }
}

class AcctionMap
{
    public function __construct(
        public string $path,
        public string $method,
        public string $actionName
    ) {
    }
    public static function __set_state(array $data): self
    {
        return new self($data["path"], $data["method"], $data["actionName"]);
    }
    //product/{id} => product/1
    public function match(string $uri)
    {
        if (!str_contains($this->path, '{')) {
            $tempPath = str_replace('/', '', $this->path);
            $tempUri = str_replace('/', '', $uri);
            if (strcasecmp($tempPath, $tempUri) === 0 && $this->method === $_SERVER['REQUEST_METHOD']) {
                return [
                    $this->actionName,
                    []
                ];
            }
            return false;
        }


        $requet_method = $_SERVER['REQUEST_METHOD'];
        // check if request method match
        if (strcasecmp($requet_method, $this->method) !== 0) {
            return false;
        }

        $regex = pattern_to_regex($this->path);

        $path_params = [];
        if (preg_match_all($regex, $uri, $path_params, PREG_SET_ORDER)) {
            $action_name = $this->actionName;
            return [
                $action_name,
                $path_params
            ];
        }
        return false;
    }
}