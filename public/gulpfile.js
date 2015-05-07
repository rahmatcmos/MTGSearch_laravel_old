var gulp = require('gulp');
var jqueryVendor = ['js/vendor/jquery.js', 'js/vendor/bootstrap.js'];
var jqueryWatch = ['js/script.js'];
var jquerySource = jqueryVendor.concat(jqueryWatch);
var scss = ['scss/style.scss', 'scss/partials/*.scss'];
var angularVendor = [
	'js/vendor/angular.min.js',
	'js/vendor/ngStorage.js'
];
var angularWatch = [
	'js/app.js',
	'js/controllers/*.js',
	'js/services/*.js'
];
var angularSource = angularVendor.concat(angularWatch);

var processWinPath = function(file) {
    var path = require('path');
    if (process.platform === 'win32') {
        file.path = path.relative('.', file.path);
        file.path = file.path.replace(/\\/g, '/');
    }
};

gulp.task('default', function() {
    gulp.watch(scss, ['sass']);
    gulp.watch(jqueryWatch, ['js']);
	gulp.watch(angularWatch, ['angular']);
});

gulp.task('sass-build', function () {
    var sass = require('gulp-sass');
    return gulp.src(scss)
        .on('data', processWinPath)
        .pipe(sass({outputStyle: 'compressed'}))
        .pipe(gulp.dest('css'));
});

gulp.task('sass', function () {
    var sass = require('gulp-sass');
    return gulp.src(scss)
        .on('data', processWinPath)
        .pipe(sass({sourceComments: 'map', outputStyle: 'compressed'}))
        .pipe(gulp.dest('css'));
});
var uglify = require('gulp-uglifyjs');
var rename = require("gulp-rename");
gulp.task('js', function () {
    return gulp.src(jquerySource)
        .pipe(uglify({mangle: false}))
		.pipe(rename('script.js'))
        .pipe(gulp.dest('js/min'));
});

gulp.task('angular', function () {
	return gulp.src(angularSource)
		.pipe(uglify({mangle: false}))
		.pipe(rename('app.js'))
		.pipe(gulp.dest('js/min'));
});