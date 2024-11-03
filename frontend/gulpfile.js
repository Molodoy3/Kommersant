import gulp from 'gulp';
import webp from 'gulp-webp';
import imagemin from 'gulp-imagemin';

// Задача для конвертации изображений в формат WebP
gulp.task("images", function () {
  return gulp.src("public/img/**/*.{jpg,jpeg,png,gif}", { encoding: false }) // Укажите путь к вашим изображениям
    .pipe(imagemin({ // Оптимизация изображений
      progressive: true,
      svgo: {
        plugins: [{ removeViewBox: false }]
      },
      interlaced: true,
      optimizationLevel: 3
    }))
    .pipe(webp())
    .pipe(gulp.dest("public/img/")); // Укажите, куда сохранить сжатые изображения
});

// Задача для отслеживания изменений
gulp.task("watch", function () {
  gulp.watch("public/img/**/*.{jpg,jpeg,png,gif}", gulp.series("images")); // Отслеживаем изменения в изображениях
});

// Задача по умолчанию
gulp.task("default", gulp.parallel("images", "watch"));
