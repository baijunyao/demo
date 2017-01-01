var gulp        = require('gulp'),
    sass        = require('gulp-sass'),
    minifyCss   = require('gulp-minify-css'),
    plumber     = require('gulp-plumber'),
    babel       = require('gulp-babel'),
    uglify      = require('gulp-uglify'),
    clearnHtml  = require("gulp-cleanhtml");
    imagemin    = require('gulp-imagemin');
    browserSync = require('browser-sync').create(),
    reload      = browserSync.reload;
    
// 定义源代码的目录和编译压缩后的目录
var src='src',
    dist='build';

// 编译全部scss 并压缩
gulp.task('css', function(){
    gulp.src(src+'/**/*.scss')
        .pipe(sass())
        .pipe(minifyCss())
        .pipe(gulp.dest(dist))
})

// 编译全部js 并压缩
gulp.task('js', function() {
  gulp.src(src+'/**/*.js')
    .pipe(plumber())
    .pipe(babel({
      presets: ['es2015']
    }))
    .pipe(uglify())
    .pipe(gulp.dest(dist));
});

// 压缩全部html
gulp.task('html', function () {
    gulp.src(src+'/**/*.html')
    .pipe(clearnHtml())
    .pipe(gulp.dest(dist));
});

// 压缩全部image
gulp.task('image', function () {
    gulp.src([src+'/**/*.+(jpg|jpeg|png|gif|bmp)'])
    .pipe(imagemin())
    .pipe(gulp.dest(dist));
});

// 自动刷新
gulp.task('server', function() {
    browserSync.init({
        server: './'
    });
    // 监听scss文件编译
    gulp.watch(src+'/**/*.scss', ['css']);   

    // 监听html文件变化后刷新页面
    gulp.watch(src+"/**/*.js", ['js']).on("change", reload);

    // 监听html文件变化后刷新页面
    gulp.watch(src+"/**/*.html", ['html']).on("change", reload);

    // 监听css文件变化后刷新页面
    gulp.watch(dist+"/**/*.css").on("change", reload);
});

// 监听事件
gulp.task('default', ['css', 'js', 'image', 'server'])
