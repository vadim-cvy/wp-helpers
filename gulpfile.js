const gulp       = require('gulp');
const sourcemaps = require('gulp-sourcemaps');

/**
 * CSS related vars
 */
const sass         = require('gulp-sass')(require('sass'));
const autoprefixer = require('gulp-autoprefixer');

/**
 * JS related vars
 */
const uglify = require('gulp-uglify');

/**
 * Common vars
 */
const srcPath   = './assets/src/';
const buildPath = './assets/build/';


function compileCss( cb )
{
  gulp.src( srcPath + 'scss/*.scss' )
    .pipe(sass({
      outputStyle: 'compressed'
    }))
    .pipe(autoprefixer({overrideBrowserslist: [
      'ie >= 10',
      'ie_mob >= 10',
      'ff >= 30',
      'chrome >= 34',
      'Safari >= 7',
      'Opera >= 23',
      'ios >= 7',
      'Android >= 4.4',
      'bb >= 10'
    ]}))
    .pipe(gulp.dest( buildPath + 'css' )
  );

  cb();
}

function compileJs( cb )
{
  return gulp.src( srcPath + 'js/**' )
    .pipe(sourcemaps.init())
    .pipe(uglify())
    .pipe(sourcemaps.write())
    .pipe(gulp.dest( buildPath + 'js' ));

  cb();
}

gulp.task( 'watch', function()
{
  gulp.watch( srcPath + 'scss/**', compileCss );

  gulp.watch( srcPath + 'js/**', compileJs );
});