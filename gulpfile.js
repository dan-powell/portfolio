// Load environment config
try {
    var config = require('./assetconfig.json');
} catch(err) {
	if (err.code == 'MODULE_NOT_FOUND') {
		console.log('assetconfig.json file missing. Please duplicate & rename the example.');
	} else {
		console.log('There is an error in the config file. Please fix it :)');
		console.log(err);
	}
	process.exit()
}


// Load plugins
var gulp        = require('gulp'),
  plumber 		= require('gulp-plumber'),
  less         	= require('gulp-less'),
  minifycss    	= require('gulp-minify-css'),
  uglify       	= require('gulp-uglify'),
  concat       	= require('gulp-concat'),
  gulpif 		= require('gulp-if');

// Load local development plugins
if (config.developmentMode) {
	var sourcemaps 	= require('gulp-sourcemaps'),
		filter       	= require('gulp-filter'),
		notify      	= require('gulp-notify'),
		shell      	= require('gulp-shell'),
		browserSync 	= require('browser-sync')
}


/* TASKS
	========================================= */

// Browser Sync
gulp.task('browser-sync', function() {
	browserSync({
    proxy: config.proxy,
    browser: config.browser
	});
});


/* LESS Tasks
	----------------------------------------- */

gulp.task('less', function() {

	if(config.tasks.less.length > 0) {
		// Loop over all the tasks and run 'em
		config.tasks.less.forEach(function(task) {

		  gulp.src(task.src)
		  	.pipe(plumber({errorHandler: notify.onError(task.name + " Error: <%= error.message %> | Extract: <%= error.extract %>")}))
		  	.pipe(gulpif(config.developmentMode, gulpif(config.sourceMaps, sourcemaps.init()) ))
		  	.pipe(less())
				.pipe(gulpif(config.minifyCss, minifycss() ))
				.pipe(gulpif(config.developmentMode, gulpif(config.sourceMaps, sourcemaps.write('.')) ))
		    .pipe(gulp.dest(task.dest))
		    .pipe(gulpif(config.developmentMode, filter('**/*.css') ))
		    .pipe(gulpif(config.developmentMode, shell(config.shell) ))
		    .pipe(gulpif(config.developmentMode, notify({ message: task.name + ' Successful' }) ))
		    .pipe(gulpif(config.developmentMode, browserSync.reload({stream:true}) ));

	  });
	} else {
		console.log('No Less tasks defined. Please add some to assetconfig.json');
	}

});


/* JS Tasks
	----------------------------------------- */

gulp.task('js-all', function() {

	if(config.tasks.js.length > 0) {

		// Loop over all the tasks and run 'em
		config.tasks.js.forEach(function(task) {

		  gulp.src(task.src)
			  .pipe(concat(task.dest))
		  	.pipe(plumber({errorHandler: notify.onError(task.name + " Error: <%= error.message %> | Extract: <%= error.extract %>")}))
		  	.pipe(gulpif(config.developmentMode, gulpif(config.sourceMaps, sourcemaps.init()) ))
		  	.pipe(uglify({
		      compress: config.uglifyJS,
		      mangle: false
		    }))
				.pipe(gulpif(config.developmentMode, gulpif(config.sourceMaps, sourcemaps.write('.')) ))
		    .pipe(gulp.dest(task.destFolder))
		    .pipe(gulpif(config.developmentMode, filter('**/*.js') ))
		    .pipe(gulpif(config.developmentMode, shell(config.shell) ))
		    .pipe(gulpif(config.developmentMode, notify({ message: task.name + ' Successful' }) ))
		    .pipe(gulpif(config.developmentMode, browserSync.reload({stream:true}) ));

		});
	} else {
		console.log('No JS tasks defined. Please add some to assetconfig.json');
	}
});


/* Copy
	----------------------------------------- */

gulp.task('copy-all', function() {

	if(config.tasks.less.length > 0) {
		// Loop over all the tasks and run 'em
		config.tasks.copy.forEach(function(task) {

		  gulp.src(task.src)
		    .pipe(gulp.dest(task.dest))
		    .pipe(notify({ message: 'Successfully copied ' + task.name }));

		});
	} else {
		console.log('No Copy tasks defined. Please add some to assetconfig.json');
	}
});


/* Publish Assets Task
	----------------------------------------- */

gulp.task('publish', function () {
    gulp.src('')
        .pipe(shell(config.shell))
        .pipe(browserSync.reload({stream:true}));
});


/* Task Groupings
	========================================= */

// Default
gulp.task('default', [], function() {
  gulp.start('watch');
});

// Kitchen sink - should be used to compile for production
gulp.task('build', [], function() {

	// Force minification when running build
	config.minifyCss = true;
  config.uglifyJS = true;
  config.sourceMaps = false;

  gulp.start('css', 'js', 'copy', 'publish');
});

// Task aliases - should always exist, but can be customised
gulp.task('css', [], function() {
  gulp.start('less');
});

gulp.task('js', [], function() {
  gulp.start('js-all');
});

gulp.task('copy', [], function() {
  gulp.start('copy-all');
});

gulp.task('cachebust', [], function() {
  gulp.start('rev');
});


// BrowserSync Reload
gulp.task('reload', [], function () {
  browserSync.reload();
});


// Custom tasks



/* Watch Files
	----------------------------------------- */

// Watch
gulp.task('watch', ['browser-sync'], function () {
	if(config.watch.length > 0) {
		config.watch.forEach(function(watch) {
	    gulp.watch(watch.files, watch.tasks);
		});
	} else {
		console.log('No watch tasks defined. Please add some to assetconfig.json');
	}
});