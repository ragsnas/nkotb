var gulp = require('gulp'),
	sass = require('gulp-sass'),
	less = require('gulp-less'),
	minifyCSS = require('gulp-minify-css'),
	uglify = require('gulp-uglify'),
	rename = require('gulp-rename'),
	jshint = require('gulp-jshint'),
	notify = require('gulp-notify'),
	concat = require('gulp-concat'),
	autoprefixer = require('gulp-autoprefixer'),
	path = require('path'),
	jshintStyle = require('jshint-stylish'),
	livereload = require('gulp-livereload');

var autoPrefixBrowserList = ['last 2 version', 'safari 5', 'ie 8', 'ie 9', 'opera 12.1', 'ios 6', 'android 4'];

var url = 'sf2.dev';
var srcPath = 'app/Resources';
var bundlesPath = 'src';
var dstPath = 'web';
var resourcesPath = srcPath + '/bower';
var libPath = srcPath + '/libs';

gulp.task('sass', function () {
	return gulp.src([
        resourcesPath + '/components-font-awesome/scss/font-awesome.scss',
        srcPath + '/sass/main.scss'
    ])
		.pipe(sass({
			style: 'compressed',
			errLogToConsole: true,
			includePaths: [
				resourcesPath,
				srcPath + '/sass'
			]
		}))
		.pipe(rename('sass.css'))
		.pipe(gulp.dest(srcPath + '/css'))
		.pipe(notify({message: 'SASS complete'}));
});

gulp.task('less', function () {
	return gulp.src([
        resourcesPath + '/bootstrap/less/bootstrap.less',
        srcPath + '/less/main.less'
    ])
    .pipe(less())
    .pipe(rename('less.css'))
    .pipe(gulp.dest(srcPath + '/css'))
    .pipe(notify({message: 'LESS complete'}));
});

gulp.task('styles', ['sass', 'less'], function () {
	return gulp.src([
        srcPath + '/css/less.css',
        srcPath + '/css/sass.css',
        resourcesPath + '/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css',
        resourcesPath + '/bootstrap-calendar/css/calendar.min.css',
        srcPath + '/css/site.css'
    ])
    .pipe(concat('main.css'))
    .pipe(autoprefixer({
        browsers: autoPrefixBrowserList,
        cascade: true
    }))
    .pipe(gulp.dest(dstPath + '/css'))
    .pipe(minifyCSS())
    .pipe(rename({
        extname: '.min.css'
    }))
    .pipe(gulp.dest(dstPath + '/css'))
    .pipe(notify({message: 'CSS complete'}));
});

gulp.task('jshint', function () {
	return gulp.src([srcPath + '/js/**/*.js'])
    .pipe(jshint())
    .pipe(jshint.reporter(jshintStyle))
    .pipe(jshint.reporter('fail'))
    .pipe(notify({message: 'JSHint complete'}));
});

gulp.task('scripts', ['jshint'], function () {
	return gulp.src([
        resourcesPath + '/jquery/dist/jquery.min.js',
        resourcesPath + '/bootstrap/dist/js/bootstrap.min.js',
        resourcesPath + '/Bootstrap-Confirmation/bootstrap-confirmation.js',
        resourcesPath + '/moment/min/moment.min.js',
        resourcesPath + '/underscore/underscore.js',
        resourcesPath + '/bootstrap-calendar/js/language/de-DE.js',
        resourcesPath + '/bootstrap-calendar/js/calendar.js',
        //libPath + '/responsive-calendar/0.9/js/responsive-calendar.min.js',
        //resourcesPath + '/bootstrap-datepicker/dist/js/locales/bootstrap-datepicker.de.min.js',
        //resourcesPath + '/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js',
        bundlesPath + '/**/js/*.js',
		srcPath + '/js/*.js'
	])
    .pipe(concat('main.js'))
    .pipe(gulp.dest(dstPath + '/js'))
    .pipe(uglify())
    .pipe(rename({
        extname: '.min.js'
    }))
    .pipe(gulp.dest(dstPath + '/js'))
    .pipe(notify({message: 'JS complete'}));
});

gulp.task('livereload', function () {
	setTimeout(function () {
		livereload.reload();
	}, 2000);
});

gulp.task('copy', function () {
	gulp.src(resourcesPath + '/bootstrap-calendar/tmpls/**/*').pipe(gulp.dest(dstPath + '/tmpls'));
	gulp.src(resourcesPath + '/bootstrap-calendar/img/**/*').pipe(gulp.dest(dstPath + '/img'));
	gulp.src(resourcesPath + '/fuelux/dist/fonts/**/*').pipe(gulp.dest(dstPath + '/fonts'));
	gulp.src(resourcesPath + '/components-font-awesome/fonts/**/*').pipe(gulp.dest(dstPath + '/fonts'));
	gulp.src(resourcesPath + '/jquery/dist/jquery.*').pipe(gulp.dest(dstPath + '/js'));
	//gulp.src(resourcesPath + '/jquery/dist/jquery.min.map').pipe(gulp.dest(dstPath + '/js'));
});

gulp.task('watch', ['deploy'], function () {
	livereload.listen();
	gulp.watch(srcPath + '/css/site.css', ['styles', 'livereload']);
	gulp.watch(srcPath + '/sass/**/*.scss', ['styles', 'livereload']);
	gulp.watch(srcPath + '/less/**/*.less', ['styles', 'livereload']);
	gulp.watch(srcPath + '/js/**/*.js', ['scripts', 'livereload']);
	gulp.watch(bundlesPath + '/**/js/*.js', ['scripts', 'livereload']);
	gulp.watch([srcPath + '/**/*.html.twig', 'src/**/*.html.twig'], ['livereload']);
});

gulp.task('default', ['watch']);
gulp.task('deploy', ['styles', 'scripts', 'copy']);