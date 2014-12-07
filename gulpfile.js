var gulp = require('gulp'),
	less = require('gulp-less'),
	rename = require('gulp-rename');

gulp.task('less', function() {
	gulp.src('resources/main.less')
		.pipe(less())
		.pipe(rename({basename:'style'}))
		.pipe(gulp.dest('public/css'));
});

gulp.task('default', function() {
	gulp.watch('resources/**/*', ['less']);
});
