require('es6-promise').polyfill();
var gulp        = require('gulp');
var sass        = require("gulp-sass");
var autoprefixer = require('gulp-autoprefixer');
var minifyCss = require('gulp-minify-css');
var concat = require('gulp-concat'); 
var uglify = require('gulp-uglify');
var gutil = require('gulp-util');

var sources = {
    php_all: '*.php',
    sass_all: ['scss/*/*.scss'],
    css_dir: './css',
    main_sass_file: 'scss/style.scss'
};

var alltasks = [
    'sass'
];

gulp.task('sass', function () {
    return gulp.src(sources.main_sass_file)
        .pipe(sass().on('error', function (err) {
            console.error('Error!', err.message);
         }))
        .pipe(autoprefixer("last 3 versions", "> 1%", "ie 8"))
        .pipe(minifyCss({compatibility: 'ie8'}))
        .pipe(gulp.dest(sources.css_dir));
});

gulp.task('default', alltasks);

gulp.task('watch', alltasks,function() {
    gulp.watch(sources.sass_all, ['sass']);
    gulp.watch(sources.js, ['js']);
});