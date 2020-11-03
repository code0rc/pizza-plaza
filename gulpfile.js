const gulp = require('gulp')

async function copyBootstrap () {
  return gulp.src('./node_modules/bootstrap/dist/**/*')
    .pipe(gulp.dest('./public/dist/bootstrap'))
}

async function copyJquery () {
  return gulp.src('./node_modules/popper.js/dist/**/*')
    .pipe(gulp.dest('./public/dist/popper'))
}

async function copyPopper() {
  return gulp.src('./node_modules/jquery/dist/**/*')
    .pipe(gulp.dest('./public/dist/jquery'))
}

module.exports.default = gulp.series(copyBootstrap, copyJquery, copyPopper);