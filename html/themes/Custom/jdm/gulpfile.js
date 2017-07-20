'use strict';

var gulp = require('gulp');
var sass = require('gulp-sass');
var uglify = require('gulp-uglify');
var uglifycss = require('gulp-uglifycss');
var path = require('path');
var fs = require('fs');
var scriptsPath = '../../../modules/custom/';

function getFolders(dir) {
  return fs.readdirSync(dir)
    .filter(function(file) {
      return fs.statSync(path.join(dir, file)).isDirectory();
    });
}
gulp.task('sass', function () {
  return gulp.src('./src/tui-be.scss')
    .pipe(sass().on('error', sass.logError))
    // uglify css
    .pipe(uglifycss({
      "maxLineLen": 80,
      "uglyComments": true
    }))
    .pipe(gulp.dest('./assets/css'));
});

gulp.task('sass:watch', function () {
  gulp.watch('./src/**/*.scss', ['sass']);
});

//Uglifies javascript
gulp.task('uglify', function() {
  var folders = getFolders(scriptsPath);
  var tasks = folders.map(function(folder) {
    return gulp.src(path.join(scriptsPath, folder, '/**/*.js'))
      // minify
      .pipe(uglify())
      // write to output again
      .pipe(gulp.dest(scriptsPath + folder ));
  });
});