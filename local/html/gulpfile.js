/**
 * Created by klim on 19.12.15.
 * http://webformyself.com/gulp-dlya-nachinayushhix/
 */

var gulp = require('gulp');
// Подключаем gulp-sass
var sass = require('gulp-sass');
// Подключаем Jade
var jade = require('gulp-jade');
// Подключаем browser-sync
var browserSync = require('browser-sync');
var gulpIf = require('gulp-if');
var uglify = require('gulp-uglify');
var cleanCss = require('gulp-clean-css');
var concat = require('gulp-concat');
var rename = require('gulp-rename');
var imagemin = require('gulp-imagemin');
var cache = require('gulp-cache');
var del = require('del');
var changed = require('gulp-changed');
var runSequence = require('run-sequence');

gulp.task('images', function() {
    return gulp.src(['app/images/**/*.+(png|jpg|gif|svg)', '!app/images/content/*'])
        .pipe(cache(imagemin({
            progressive: true,
            svgoPlugins: [{removeViewBox: false}]
        })))
        .pipe(gulp.dest('../templates/fairytales/images'));
});

gulp.task('browserSync', function() {
    browserSync({
        server: {
            baseDir: 'app'
        }
    });
});

gulp.task('sass', function() {
    return gulp.src(['app/scss/**/*.scss', '!app/scss/**/preload_styles_*.scss', '!app/scss/**/preload_styles.scss'])
        .pipe(sass()) // используем gulp-sass
        .pipe(gulp.dest('app/css'))
        // .pipe(gulp.dest('dist/css'))
        .pipe(browserSync.reload({
            stream: true
        }))
});

gulp.task('styles_min', ['sass'], function() {
    return gulp.src(['app/css/*.css', '!app/css/*.min.css', '!app/css/print.css', '!app/css/preload_styles.css', '!app/css/preload_styles*.css'])
        .pipe(concat('styles.min.css'))
        .pipe(cleanCss())
        .pipe(gulp.dest('../templates/fairytales/css'));
});

gulp.task('js_min', function () {
    return gulp.src(['app/js/*.js', '!app/js/*.min.js', '!app/js/*jquery*', '!app/js/*bootstrap*'])
        .pipe(concat('functions.min.js'))
        .pipe(uglify())
        .pipe(gulp.dest('../templates/fairytales/js'));
});

gulp.task('copy_min_js', function () {
    return gulp.src('app/js/*.min.js')
        .pipe(gulp.dest('../templates/fairytales/js'));
});

gulp.task('copy_js', function () {
    return gulp.src(['!app/js/*.min.js', 'app/js/*jquery*', 'app/js/*bootstrap*'])
        .pipe(uglify())
        .pipe(rename({suffix: ".min"}))
        .pipe(gulp.dest('../templates/fairytales/js'));
});

gulp.task('components_js_min', function () {
    return gulp.src(['../templates/fairytales/components/**/*.js', '!../templates/fairytales/components/**/*.min.js', '!../templates/fairytales/components/**/*.map.js'])
        .pipe(uglify())
        .pipe(rename({suffix: ".min"}))
        .pipe(gulp.dest(function(file){
            return file.base;
        }))
});

gulp.task('components_js_min_one', function (file) {

    return gulp.src(['../templates/fairytales/components/**/*.js', '!../templates/fairytales/components/**/*.min.js', '!../templates/fairytales/components/**/*.map.js'])
        // .pipe(changed(function(file){return file.base;}, {hasChanged: changed.compareContents}))
        .pipe(uglify())
        .pipe(rename({suffix: ".min"}))
        .pipe(gulp.dest(function(file){
            return file.base;
        }))
});

gulp.task('jade', function() {
    gulp.src('app/jade/*.jade')
        .pipe(changed('app', {hasChanged: changed.compareContents}))
        .pipe(jade({
            pretty: true
        }))
        .pipe(gulp.dest('app'))
});

gulp.task('fonts', function() {
    return gulp.src('app/fonts/**/*')
        .pipe(gulp.dest('../templates/fairytales/fonts'))
});

gulp.task('dist_js', function() {
    return gulp.src('app/js/functions.js')
        .pipe(gulp.dest('../templates/fairytales/js'))
});

gulp.task('dist_sass', function() {
    return gulp.src('app/css/style.css')
        .pipe(gulp.dest('../templates/fairytales/css'))
});

gulp.task('clean', function(callback) {
    del('../templates/fairytales/css');
    del('../templates/fairytales/js');
    del('../templates/fairytales/*.html');
    return cache.clearAll(callback);
});

gulp.task('cleanAll', function(callback) {
    del('../templates/fairytales/css');
    del('../templates/fairytales/js');
    del('../templates/fairytales/*.html');
    del('../templates/fairytales/images');
    del('../templates/fairytales/font');
    return cache.clearAll(callback);
});

gulp.task('build', function (callback) {
    runSequence('clean',
        ['sass'],
        callback
    )
});

gulp.task('buildAll', function (callback) {
    runSequence('cleanAll',
        ['styles_min', 'copy_min_js', 'copy_js', 'js_min', 'images', 'fonts'],
        callback
    )
});

// Gulp watch
gulp.task('watch', ['browserSync', 'styles_min', 'js_min', 'components_js_min_one'], function() {
    gulp.watch(['app/scss/**/*.scss', '!app/scss/preload_styles.scss', '!app/scss/preload_styles_*.scss'], ['styles_min']);
    // gulp.watch(['app/scss/**/*.scss'], ['sass']);
    gulp.watch('app/jade/**/*.jade', ['jade']);
    gulp.watch('app/js/**/*.js', ['js_min']);
    // gulp.watch('app/css/**/*.css', ['styles_min']);

    // Обновляем браузер при любых изменениях в HTML или JS
    gulp.watch('app/*.html', browserSync.reload);
    gulp.watch('app/js/**/*.js', browserSync.reload);
    gulp.watch('app/css/**/*.css', browserSync.reload);
});

// Gulp watch
gulp.task('preload_watch', ['preload_styles_others'], function() {
    gulp.watch(['app/scss/preload_styles_*.scss', 'app/scss/_*.scss'], ['preload_styles_others']);
});

gulp.task('default', function (callback) {
    runSequence(['styles_min', 'js_min', 'browserSync', 'watch'],
        callback
    )
});