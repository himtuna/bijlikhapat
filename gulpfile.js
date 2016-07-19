var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function(mix) {
    mix.sass('app.scss');
});


var htmlmin = require('gulp-htmlmin');
var gulp = require('gulp');

gulp.task('compress', function() {
    var opts = {
        collapseWhitespace:    true,
        removeAttributeQuotes: true,
        removeComments:        true,
        minifyJS:              true
    };

    return gulp.src('./storage/framework/views/*')
               .pipe(htmlmin(opts))
               .pipe(gulp.dest('./storage/framework/views/'));
});