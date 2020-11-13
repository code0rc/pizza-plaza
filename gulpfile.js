const gulp = require('gulp')
const concat = require('gulp-concat')
const babel = require('gulp-babel')
const uglify = require('gulp-uglify')
const rename = require('gulp-rename')

async function copyBootstrap () {
  return gulp.src('./node_modules/bootstrap/dist/**/*')
    .pipe(gulp.dest('./public/dist/bootstrap'))
}

async function copyJquery () {
  return gulp.src('./node_modules/popper.js/dist/**/*')
    .pipe(gulp.dest('./public/dist/popper'))
}

async function copyPopper () {
  return gulp.src('./node_modules/jquery/dist/**/*')
    .pipe(gulp.dest('./public/dist/jquery'))
}

async function bundleScripts () {
  return gulp.src([
    './src/components/ArticleTileList.js',
    './src/components/ArticleTile.js',
    './src/components/OrderSummaryListItem.js',
    './src/components/OrderSummaryList.js',
    './src/components/OrderSummary.js',
    './src/ViewOrder.js',
  ])
    .pipe(concat('vue-app.js'))
    .pipe(babel({ presets: ['@babel/env'] }))
    .pipe(gulp.dest('./public/assets/js'))
    .pipe(uglify())
    .pipe(rename({ suffix: '.min' }))
    .pipe(gulp.dest('./public/assets/js'))
}

module.exports.default = gulp.parallel(copyBootstrap, copyJquery, copyPopper, bundleScripts)
module.exports.all = module.exports.default
module.exports.styles = gulp.parallel(copyBootstrap, copyJquery, copyPopper)
module.exports.scripts = gulp.parallel(bundleScripts)