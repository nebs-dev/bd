/* File: gulpfile.js */

// grab our gulp packages
var gulp       = require('gulp');
var gutil      = require('gulp-util');


var jshint     = require('gulp-jshint');
var sass       = require('gulp-sass');
var concat     = require('gulp-concat');
var sourcemaps = require('gulp-sourcemaps');
var plumber = require('gulp-plumber');


input  = {
	'sass': 'sources/scss/**/*.scss',
	'javascript': 'sources/javascript/**/*.js',
	'vendorjs': 'public/assets/javascript/vendor/**/*.js'
  },

  output = {
    'stylesheets': 'public/assets/stylesheets',
    'javascript': 'public/assets/javascript'
  };


// define the default task and add the watch task to it
gulp.task('default', ['watch']);

// configure the jshint task
gulp.task('jshint', function() {
	return gulp.src(input.javascript)
	.pipe(jshint())
	.pipe(jshint.reporter('jshint-stylish'));
	});

// configure the node-sass(libsass) task
gulp.task('build-css', function() {
  return gulp.src(input.sass)
  .pipe(plumber())
  .pipe(sourcemaps.init())
  .pipe(sass())
  .pipe(sourcemaps.write())
  .pipe(gulp.dest(output.stylesheets));
  });

// concat javascript files, minify if --type production
gulp.task('build-js', function() {
  return gulp.src(input.javascript)
  .pipe(sourcemaps.init())
  .pipe(concat('bundle.js'))
      //only uglify if gulp is ran with '--type production'
      .pipe(gutil.env.type === 'production' ? uglify() : gutil.noop()) 
      .pipe(sourcemaps.write())
      .pipe(gulp.dest(output.javascript));
      });

// configure which files to watch and what tasks to use on file changes
gulp.task('watch', function() {
  gulp.watch(input.javascript, ['jshint', 'build-js']);
  gulp.watch(input.sass, ['build-css']);
  });
