let project_folder = "css";
let sourse_folder = "src";

let path = {
  build: {
    css: project_folder + "/",
  },
  src: {
    css: sourse_folder + "/scss/style.scss",
  },
  watch: {
    css: sourse_folder + "/scss/**/*.scss",
  },
  clean: "./" + project_folder + "/",
};

let { src, dest } = require("gulp"),
  gulp = require("gulp"),
  del = require("del"),
  scss = require("gulp-sass")(require("sass")),
  autoprefixer = require("gulp-autoprefixer"),
  gcmq = require("gulp-group-css-media-queries");

function css() {
  return gulp
    .src(path.src.css)
    .pipe(
      scss({
        outputStyle: "expanded",
      })
    )
    .pipe(
      autoprefixer({
        overrideBrowserslist: ["last 5 version"],
        cascade: true,
      })
    )
    .pipe(gcmq())
    .pipe(dest(path.build.css));
}

function watchFiles() {
  gulp.watch([path.watch.css], css);
}

function clean() {
  return del(path.clean);
}

let build = gulp.series(clean, css);
let watch = gulp.parallel(build, watchFiles);

exports.css = css;
exports.build = build;
exports.watch = watch;
exports.default = watch;
