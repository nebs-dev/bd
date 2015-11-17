'use strict';
var gulp = require('gulp');
var gulpLoadPlugins = require('gulp-load-plugins');
var plugins = gulpLoadPlugins();
var sass = require('gulp-sass');

var imagemin = require('gulp-imagemin');
var pngquant = require('imagemin-pngquant');
var browserSync = require('browser-sync');

var reload = browserSync.reload;



// For windows users setup a growl notifier
//var growlNotifier = growl();

//JAVASCRIPT TASK: write one minified js file out of:
// -- vendors (bower components)
// -- user js files
var errorHandler = function(err) {
    console.log(err);
};

gulp.task('vendor', function() {
    return gulp.src(
            // add bower component main.js file in this array
            [

                'bower_components/jquery/dist/jquery.js',
                'bower_components/modernizr/modernizr.js',
                'bower_components/fastclick/lib/fastclick.js',
                'bower_components/foundation/js/foundation.js',
                'user_components/jquery.mockjax.js',
                'user_components/jquery.autocomplete.js',
                'bower_components/foundation-datepicker/js/foundation-datepicker.js',
                'bower_components/iCheck/icheck.js',
                'bower_components/datetimepicker/jquery.datetimepicker.js',
                'bower_components/ionrangeslider/js/ion.rangeSlider.js',
                'bower_components/flexslider/jquery.flexslider.js'
            ])
        .pipe(plugins.concat('vendor.min.js'))
        //.pipe(plugins.uglify())
        .pipe(plugins.sourcemaps.write('./'))
        .pipe(gulp.dest('dist/js'));
});
// disabled - using sublime linter - jscs instead
gulp.task('jscs', function() {
    return gulp.src('./app/scripts/**/*.js')
        .pipe(plugins.jscs())
        .pipe(plugins.notify({
            title: 'JSCS',
            message: 'JSCS Passed. Let it fly!'
        }));
});
// Lint + Concat + Uglify scripts from app/js dir
gulp.task('main-scripts', function() {
    return gulp.src('app/scripts/**/*.js')
        .pipe(plugins.plumber({
            errorHandler: errorHandler
        }))
        .pipe(plugins.jshint('.jshintrc'))
        .pipe(plugins.jshint.reporter('jshint-stylish'))
        .pipe(plugins.jshint.reporter('fail'))
        .pipe(plugins.concat('main.min.js'))
        //.pipe(plugins.uglify())
        .pipe(plugins.sourcemaps.write('./'))
        .pipe(gulp.dest('dist/js'))
        .pipe(plugins.notify({
            title: 'JSHint',
            message: 'JSHint Finished. Let it fly!',
        }))
        .pipe(reload({
            stream: true
        }));

});


// Compile sass into CSS & auto-inject into browsers
gulp.task('sass', function() {
    gulp.src('app/styles/**/*.scss')
        .pipe(plugins.sourcemaps.init())
        .pipe(plugins.sass({
            outputStyle: 'compressed', // libsass doesn't support expanded yet
            precision: 10,
            includePaths: ['.'],
            onError: console.error.bind(console, 'Sass error:')
        }))
        .pipe(plugins.postcss([
            require('autoprefixer-core')({
                browsers: ['last 1 version']
            })
        ]))
        .pipe(plugins.sourcemaps.write())
        .pipe(gulp.dest('dist/css'))
        /* Reload the browser CSS after every change */
        .pipe(reload({
            stream: true
        }));
});

// ### Fonts
gulp.task('copyfonts', function() {
    gulp.src('app/fonts/**/*.{ttf,woff,eof,svg}')
        .pipe(gulp.dest('dist/css/fonts'));
});


// #Images
gulp.task('imagemin', function() {
    return gulp.src('app/images/**/*')
        .pipe(imagemin({
            progressive: true,
            svgoPlugins: [{
                removeViewBox: false
            }],
            use: [pngquant()]
        }))
        .pipe(gulp.dest('dist/images'));
});

/* Reload task */
gulp.task('bs-reload', function() {
    browserSync.reload();
});

gulp.task('browser-sync', function() {
    browserSync.init(['dist/css/main.css', 'css/*.css', 'js/*.js'], {
        /*
        I like to use a vhost, WAMP guide: https://www.kristengrote.com/blog/articles/how-to-set-up-virtual-hosts-using-wamp, XAMP guide: http://sawmac.com/xampp/virtualhosts/
        */
        proxy: 'http://localhost/best/web/app_dev.php'
            /* For a static server you would use this: */
            /*
            server: {
                baseDir: './'
            }
            */
    });
});

/* Watch scss, js and html files, doing different things with each. */
gulp.task('serve', ['sass', 'browser-sync'], function() {
    /* Watch scss, run the sass task on change. */
    gulp.watch(['app/styles/**/*.scss'], ['sass'])
        /* Watch app.js file, run the scripts task on change. */
    gulp.watch(['app/scripts/**/*.js'], ['main-scripts'])
    gulp.watch(['app/fonts/**/*'], ['copyfonts'])
    gulp.watch(['app/images/**/*'], ['imagemin'])


    /* Watch .html files, run the bs-reload task on change. */
    //gulp.watch(['*.html'], ['bs-reload']);
});
