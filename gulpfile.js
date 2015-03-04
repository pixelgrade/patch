var gulp 		= require('gulp'),
	sass 		= require('gulp-ruby-sass'),
	prefix 		= require('gulp-autoprefixer'),
	exec 		= require('gulp-exec'),
	replace 	= require('gulp-replace'),
	clean 		= require('gulp-clean'),
	minify 		= require('gulp-minify-css'),
	livereload 	= require('gulp-livereload'),
	concat 		= require('gulp-concat'),
	notify 		= require('gulp-notify'),
	beautify 	= require('gulp-beautify'),
	csscomb 	= require('gulp-csscomb'),
	cmq 		= require('gulp-combine-media-queries'),
	chmod 		= require('gulp-chmod');

jsFiles = [
	'./assets/js/vendor/*.js',
	'./assets/js/main/wrapper_start.js',
	'./assets/js/main/shared_vars.js',
	'./assets/js/modules/*.js',
	'./assets/js/main/main.js',
	'./assets/js/main/functions.js',
	'./assets/js/main/wrapper_end.js'
];


var options = {
	silent: true,
	continueOnError: true // default: false
};

// styles related
gulp.task('styles-dev', function () {
	return gulp.src('assets/scss/**/*.scss')
		.pipe(sass({sourcemap: true, style: 'compact'}))
			.on('error', function (e) {
				console.log(e.message);
			})
		// .pipe(prefix("last 1 version", "> 1%", "ie 8", "ie 7"))
		// .pipe(chmod(644))
		.pipe(gulp.dest('./'))
		.pipe(livereload());
});

gulp.task('styles', function () {
	return gulp.src('assets/scss/**/*.scss')
		.pipe(sass({sourcemap: false, style: 'expanded'}))
		.pipe(prefix("last 1 version", "> 1%", "ie 8", "ie 7"))
		// .pipe(cmq())
		.pipe(csscomb())
		.pipe(chmod(644))
		.pipe(gulp.dest('./'));
});

gulp.task('styles-watch', function () {
	livereload.listen();
	return gulp.watch('assets/scss/**/*.scss', ['styles']);
});


// javascript stuff
gulp.task('scripts', function () {
	return gulp.src(jsFiles)
		.pipe(concat('main.js'))
		.pipe(beautify({indentSize: 2}))
		.pipe(chmod(644))
		.pipe(gulp.dest('./assets/js/'));
});

gulp.task('scripts-watch', function () {
	livereload.listen();
	return gulp.watch('assets/js/**/*.js', ['scripts']);
});

gulp.task('watch', function () {
	gulp.watch('assets/scss/**/*.scss', ['styles-dev']);
	gulp.watch('assets/js/**/*.js', ['scripts']);
});

// usually there is a default task for lazy people who just wanna type gulp
gulp.task('start', ['styles', 'scripts'], function () {
	// silence
});

gulp.task('server', ['styles', 'scripts'], function () {
	console.log('The styles and scripts have been compiled for production! Go and clear the caches!');
});


/**
 * Copy theme folder outside in a build folder, recreate styles before that
 */
gulp.task('copy-folder', ['styles', 'scripts'], function () {

	return gulp.src('./')
		.pipe(exec('rm -Rf ./../build; mkdir -p ./../build/patch; rsync -av --exclude="node_modules" ./* ./../build/patch/', options));
});

/**
 * Clean the folder of unneeded files and folders
 */
gulp.task('build', ['copy-folder'], function () {

	// files that should not be present in build
	files_to_remove = [
		'**/codekit-config.json',
		'node_modules',
		'config.rb',
		'gulpfile.js',
		'package.json',
		'pxg.json',
		'build',
		'css',
		'.idea',
		'**/.svn*',
		'**/*.css.map',
		'**/.sass*',
		'.sass*',
		'**/.git*',
		'*.sublime-project',
		'*.sublime-workspace',
		'.DS_Store',
		'**/.DS_Store',
		'__MACOSX',
		'**/__MACOSX',
		'README.md'
	];

	files_to_remove.forEach(function (e, k) {
		files_to_remove[k] = '../build/patch/' + e;
	});

	return gulp.src(files_to_remove, {read: false})
		.pipe(clean({force: true}));
});

/**
 * Create a zip archive out of the cleaned folder and delete the folder
 */
gulp.task('zip', ['build'], function(){

	return gulp.src('./')
		.pipe(exec('cd ./../; rm -rf patch.zip; cd ./build/; zip -r -X ./../patch.zip ./patch; cd ./../; rm -rf build'));

});

// usually there is a default task  for lazy people who just wanna type gulp
gulp.task('default', ['start'], function () {
	// silence
});

/**
 * Short commands help
 */

gulp.task('help', function () {

	var $help = '\nCommands available : \n \n' +
		'=== General Commands === \n' +
		'start              (default)Compiles all styles and scripts and makes the theme ready to start \n' +
		'zip               	Generate the zip archive \n' +
		'build						  Generate the build directory with the cleaned theme \n' +
		'help               Print all commands \n' +
		'=== Style === \n' +
		'styles             Compiles styles \n' +
		'styles-prod        Compiles styles in production mode \n' +
		'styles-dev         Compiles styles in development mode \n' +
		'=== Scripts === \n' +
		'scripts            Concatenate all js scripts \n' +
		'scripts-dev        Concatenate all js scripts and live-reload \n' +
		'=== Watchers === \n' +
		'watch              Watches all js and scss files \n' +
		'styles-watch       Watch only styles\n' +
		'scripts-watch      Watch scripts only \n';

	console.log($help);

});