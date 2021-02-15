'use strict';

const { series, watch, src, dest, parallel } = require('gulp');

// Gulp plugins & utilities
const del = require('del');
const zip = require('gulp-zip');
const wpPot = require('gulp-wp-pot');

/**
 * Copy files for production
 */
function copyFiles() {
  return src([
    '**',
    '!*.map',
    '!node_modules/**',
    '!dist/**',
    '!sass/**',
    '!.git/**',
    '!.github/**',
    '!gulpfile.js',
    '!package.json',
    '!package-lock.json',
    '!.editorconfig',
    '!.gitignore',
    '!.jshintrc',
    '!.DS_Store',
    '!*.map',
  ]).pipe(dest('dist/smart-recent-posts-widget/'));
}

/**
 * Clean folder
 */
function clean() {
  return del(['dist/**', 'dist'], { force: false });
}

/**
 * Zip folder
 */
function zipped() {
  return src(['dist/**'])
    .pipe(zip('smart-recent-posts-widget.zip'))
    .pipe(dest('dist/'));
}

/**
 * Generate .pot file
 */
function language() {
  return src(['**/*.php', '!dist/**/*'])
    .pipe(
      wpPot({
        domain: 'smart-recent-posts-widget',
        package: 'Smart Recent Posts Widget',
      })
    )
    .pipe(dest('languages/smart-recent-posts-widget.pot'));
}

/**
 * Tasks
 */
exports.default = series(language, copyFiles, zipped);
exports.clean = clean;
