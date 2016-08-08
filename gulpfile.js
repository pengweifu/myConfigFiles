'use strict';

var gulp = require('gulp');
var $ = require('gulp-load-plugins')();

gulp.task('lint:js', function () {
  return gulp.src([
    './gitHookTest/js/*.js',
  ]).pipe($.jshint())
    .pipe($.jshint.reporter('jshint-stylish'))
    .pipe($.jshint.reporter('fail'));
});
