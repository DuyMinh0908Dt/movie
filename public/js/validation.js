const validations = {
  required: (value) => {
    if (value === undefined || value === null) {
      return false;
    }
    if (typeof value === "string") {
      return value.trim().length > 0;
    }
    if (Array.isArray(value)) {
      return value.length > 0;
    }
    return true;
  },
  email: (value) => /\S+@\S+\.\S+/.test(value),
  minLength: (value, length) => value.length >= length,
  maxLength: (value, length) => value.length <= length,
  number: (value) => !isNaN(value),
  min: (value, min) => value >= min,
  max: (value, max) => value <= max,
  pattern: (value, pattern) => new RegExp(pattern).test(value),
  between: (value, [min, max]) => value >= min && value <= max,
  custom: (value, callback) => callback(value),
};
window.validationsUtils = validations;
class Validator {
  constructor() {
    this.validationRules = {};
  }

  /**
   * rules
   * @typedef {Object} Rule
   * @property {string} value - Giá trị cần so sánh
   * @property {string} message - Thông báo lỗi
   */
  /**
   * Đăng ký một trường cần kiểm tra
   * @param {string} field - Tên trường cần kiểm tra
   * @param {Object.<string, Rule>} rules - Danh sách các quy tắc kiểm tra
   */
  register(field, rules) {
    if (!this.validationRules[field]) {
      this.validationRules[field] = [];
    }
    Object.keys(rules).forEach((ruleName) => {
      const ruleConfig = rules[ruleName];
      const validationFunction = validations[ruleName];
      if (validationFunction) {
        this.validationRules[field].push({
          validationFunction,
          ruleConfig,
        });
      }
    });
  }
  validate(data) {
    const errors = {};
    Object.keys(data).forEach((field) => {
      if (this.validationRules[field]) {
        this.validationRules[field].forEach((rule) => {
          const { validationFunction, ruleConfig } = rule;
          const isValid = validationFunction(data[field], ruleConfig.value);
          if (!isValid) {
            if (!errors[field]) {
              errors[field] = [];
            }
            errors[field].push(ruleConfig.message || "Trường này không hợp lệ");
          }
        });
      }
    });
    return errors;
  }
  validateField(field, value) {
    const rules = this.validationRules[field];
    const errors = [];
    if (rules) {
      rules.forEach((rule) => {
        const { validationFunction, ruleConfig } = rule;
        const isValid = validationFunction(value, ruleConfig.value);
        if (!isValid) {
          errors.push(ruleConfig.message || "Trường này không hợp lệ");
        }
      });
    }
    return errors;
  }
}

class FormValidator extends Validator {
  constructor() {
    super();
    this.elements = {};
  }
  register(
    field,
    rules,
    {
      validateOn = "input",
      renderErrorHtml = (errors) => errors.join(", "),
      errorElementSelector = `#${field}-error`,
      elementSelector = `#${field}`,
    }
  ) {
    super.register(field, rules);
    document
      .querySelector(elementSelector)
      .addEventListener(validateOn, (e) => {
        const errors = super.validateField(field, e.target.value);
        const errorElement = document.querySelector(errorElementSelector);
        if (errors.length) {
          errorElement.innerText = renderErrorHtml(errors);
          errorElement.style.display = "block";
        } else {
          errorElement.innerHTML = "";
          errorElement.setAttribute("hidden", "true");
        }
      });
  }
}

document.addEventListener("alpine:init", () => {
  console.log("Alpine is initialized");
  Alpine.data("formValidator", (validationRules) => {
    return {
      errors: {},
      data: {},
      validate: null,
      reset() {
        this.errors = {};
        this.data = {};
        Object.keys(validationRules).forEach((field) => {
          this.data[field] = validationRules[field].default || "";
        });
      },
      init() {
        this.validator = new Validator();
        Object.keys(validationRules).forEach((field) => {
          this.validator.register(field, validationRules[field]);
          this.data[field] = validationRules[field].default || "";
          document.getElementById(field)?.addEventListener("focus", (e) => {
            this.errors[field] = null;
          });
        });
        this.validate = () => {
          this.errors = this.validator.validate(this.data);
          if (Object.keys(this.errors).length) {
            return false;
          }
          return true;
        };
      },
      parseAxiosError(error) {
        if (error.response?.status === 400) {
          const tempValidateErrors = error.response.data.errors;
          for (const field in tempValidateErrors) {
            if (typeof tempValidateErrors[field] === "string") {
              this.errors[field] = [tempValidateErrors[field]];
            } else {
              this.errors[field] = tempValidateErrors[field];
            }
          }
        } else {
          this.errors = { global: ["Có lỗi xảy ra, vui lòng thử lại sau"] };
        }
      },
    };
  });
});

window.Validator = Validator;
window.FormValidator = FormValidator;
