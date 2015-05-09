// Load Gulp plugins
var gulp        = require('gulp');
var plumber = require('gulp-plumber');
var less = require('gulp-less');
var uglify = require('gulp-uglify');
var filter      = require('gulp-filter');
var concat = require('gulp-concat');
var streamqueue = require('streamqueue');
var sourcemaps = require('gulp-sourcemaps');
var notify = require("gulp-notify");
var browserSync = require('browser-sync');
var mergeStream = require('merge-stream');
var shell = require('gulp-shell');

var config = require('./assetconfig.json');

// Process LESS
gulp.task('less', function () {

    // Merges multiple streams together - Allows multiple groups of scripts to be processed through one pipe.
    var mergeSources = function(i, source) {
        //Check if this is the last iteration or not
        if (i > 0){
            // Merge the current stream with the next by a calling this function recursively
            return mergeStream(mergeSources(i - 1, source), gulp.src(source[i].input));
        } else {
            // Return last stream
            return gulp.src(source[i].input).pipe(concat(source[i].output));
        }
    };

    // Merge the groups of scripts in to a single stream
    mergeSources(config.less.length - 1, config.less)

	    // Use plumber to output errors through Notify
	    .pipe(plumber({errorHandler: notify.onError("Error: <%= error.message %> | Extract: <%= error.extract %>")}))

	    // initialize source-maps
	    .pipe(sourcemaps.init())

	    // Do the processing
	    .pipe(less({
            compress: false
        }))

	    // Write source maps to file
	    .pipe(sourcemaps.write('.'))

	    // Write processed data to file
	    .pipe(gulp.dest('.'))

	    // Filtering stream to only relevant files get passed to browser sync for injection & Notify upon successful completion!
	    .pipe(filter('**/*.css'))
	    .pipe(notify("Less Gulped!"))
});

// Process JS
gulp.task('js', function() {

    // Merges multiple streams together - Allows multiple groups of scripts to be processed through one pipe.
    var mergeSources = function(i, source) {
        //Check if this is the last iteration or not
        if (i > 0){
            // Merge the current stream with the next by a calling this function recursively
            return mergeStream(mergeSources(i - 1, source), gulp.src(source[i].input).pipe(concat(source[i].output)));
        } else {
            // Return last stream
            return gulp.src(source[i].input).pipe(concat(source[i].output));
        }
    };

    // Merge the groups of scripts in to a single stream
    mergeSources(config.js.length - 1, config.js)

        // Use plumber to output errors through Notify
        .pipe(plumber({errorHandler: notify.onError("Error: <%= error.message %> | Extract: <%= error.extract %>")}))

        // initialize source-maps
        .pipe(sourcemaps.init())

        // Do the processing
        .pipe(uglify({
            compress: false,
            mangle: false
        }))

        // Write source maps to file
        .pipe(sourcemaps.write('.'))

        // Write processed data to file
        .pipe(gulp.dest('.'))

        // Notify upon successful completion & reload page via Browser-sync
        .pipe(notify("Scripts Gulped!"))
});



gulp.task('copy', function() {
    gulp.src(config.copy[0].input)
        .pipe(gulp.dest(config.copy[0].output));
});


gulp.task('publish', function () {
    return gulp.src('')
        .pipe(shell([
            'cd /Users/dan/Codebase/portfolio && php artisan vendor:publish --force'
        ]))
        .pipe(browserSync.reload({stream:true}));
});


// Browser-Sync
gulp.task('browser-sync', function() {
    browserSync({
        proxy: config.proxy,
        browser: "google chrome"
    });
});


// Reload all Browsers using browser-sync
gulp.task('bs-reload', function () {
    browserSync.reload();
});


// Watch files, doing different things with each type.
gulp.task('default', ['browser-sync'], function () {
    gulp.watch("./src/resources/assets/less/**/*.less", ['less', 'publish']);
    gulp.watch("./src/resources/assets/js/**/*.js", ['js', 'publish']);
    gulp.watch("./src/resources/views/**/*.php", ['publish']);
    gulp.watch("./src/public/admin/views/**/*.html", ['publish']);
});



