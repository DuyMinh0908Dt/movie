import esbuild from "esbuild";
import fs from "fs";
import path from "path";
const SOURCE_FOLDER = "./public/rawjs";

function* walk(dir) {
  const files = fs.readdirSync(dir, { withFileTypes: true });
  for (const file of files) {
    if (file.isDirectory()) {
      yield* walk(path.join(dir, file.name));
    } else {
      if (file.name.endsWith(".js") && !file.name.endsWith(".ignore.js")) {
        yield path.join(dir, file.name);
      }
    }
  }
}

function buildFile(inFile, out) {
  esbuild
    .build({
      entryPoints: [inFile],
      bundle: true,
      minify: true,
      target: "es2017",
      sourcemap: true,
      outfile: out,
    })
    .then(() => {
      console.log(`Built ${inFile} to ${out}`);
    });
}

function work() {
  for (const file of walk(SOURCE_FOLDER)) {
    const outFile = file.replace("rawjs", "bundle");
    const outDir = path.dirname(outFile);
    if (!fs.existsSync(outDir)) {
      fs.mkdirSync(outDir, { recursive: true });
    }
    buildFile(file, outFile);
  }
}
work();
