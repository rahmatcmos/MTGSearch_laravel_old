var gulp = require('gulp');
var jqueryVendor = ['public/js/vendor/jquery.js', 'public/js/vendor/bootstrap.js'];
var jqueryWatch = ['public/js/script.js'];
var jquerySource = jqueryVendor.concat(jqueryWatch);
var scss = ['public/scss/style.scss', 'public/scss/partials/*.scss'];
var angularVendor = [
	'public/js/vendor/angular.min.js',
	'public/js/vendor/ngStorage.js'
];
var angularWatch = [
	'public/js/app.js',
	'public/js/controllers/*.js',
	'public/js/services/*.js'
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
        .pipe(gulp.dest('public/js/min'));
});

gulp.task('angular', function () {
	return gulp.src(angularSource)
		.pipe(uglify({mangle: false}))
		.pipe(rename('app.js'))
		.pipe(gulp.dest('public/js/min'));
});