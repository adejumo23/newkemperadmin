const gulp = require('gulp');
const sass = require('gulp-sass');
const minifyCSS = require('gulp-csso');
const autoprefixer = require('gulp-autoprefixer');
const cleanCSS = require('gulp-clean-css');
const concat = require('gulp-concat');
const addsrc = require('gulp-add-src');

gulp.task('styles', function() {
    gulp.src('sass/**/*.scss')
        .pipe(sass({outputStyle: 'nested'}).on('error', sass.logError))
        .pipe(autoprefixer({
            browsers: ['last 10 versions'],
            cascade: false
        }))
        .pipe(gulp.dest('./css/'));
});

gulp.task('build-js', function() {
    gulp.src(['./js/modules/_intro-mdb-admin.js', './js/modules/buttons.js', './js/modules/cards.js', './js/modules/collapsible.js', './js/modules/dropdown.js', './js/modules/file-input.js', './js/modules/forms-free.js', './js/modules/hammer.js', './js/modules/jquery.easing.js', './js/modules/jquery.hammer.js', './js/modules/jarallax-video.js', './js/modules/jarallax.js', './js/modules/jquery.sticky.js', './js/modules/lightbox.js', './js/modules/material-select.js', './js/modules/mdb-autocomplete.js', './js/modules/picker.js', './js/modules/picker-date.js', './js/modules/picker-time.js', './js/modules/range-input.js', './js/modules/scrollbar.js', './js/modules/scrolling-navbar.js', './js/modules/sidenav.js', './js/modules/smooth-scroll.js', './js/modules/velocity.min.js', './js/modules/waves.js', './js/modules/wow.js', './js/modules/chart.js', './js/modules/toastr.js', './js/modules/chips.js'])
        .pipe(concat('mdb.js'))
        .pipe(gulp.dest('./js/'))
});

//Watch task
gulp.task('default',function() {
    gulp.watch('sass/**/*.scss',['styles']);
});