import {
  src, dest, watch, parallel, series,
} from 'gulp'
import { pkg, paths, serverrc } from './gulp/config'

const PRODUCTION = pkg.yargs.argv.prod

const scssPaths = paths.scss
const cssPaths = paths.css
const jsPaths = paths.js.concat

const server = pkg.browserSync.create()
export const serve = (done) => {
  server.init(serverrc)
  done()
}

const reload = (done) => {
  server.reload()
  done()
}

const processScss = (source, name, destination) => src(source)
  .pipe(pkg.plumber())
  .pipe(pkg.if(!PRODUCTION, pkg.sourcemaps.init()))
  .pipe(pkg.sass().on('error', pkg.sass.logError))
  .pipe(pkg.rename(name))
  .pipe(pkg.if(!PRODUCTION, pkg.sourcemaps.write()))
  .pipe(dest(destination))

export const scss = () => {
  const streams = pkg.mergeStream()
  streams.add(processScss(scssPaths.admin.src, scssPaths.admin.name, scssPaths.admin.dest))
  streams.add(processScss(scssPaths.frontend.src, scssPaths.frontend.name, scssPaths.frontend.dest))

  return streams
}

export const css = () => src(cssPaths.concat.src)
  .pipe(pkg.concat('theme.css'))
  .pipe(pkg.if(PRODUCTION, pkg.postcss([pkg.autoprefixer({ grid: true, browsers: ['>1%'] }), pkg.mqpacker({ sort: true })])))
  .pipe(pkg.if(PRODUCTION, pkg.cleanCss({ compatibility: '*' })))
  .pipe(dest(cssPaths.concat.dest))
  .pipe(server.stream())


export const transpile = (source, name, destination) => src(source)
  .pipe(pkg.plumber())
  .pipe(pkg.concat(name))
  .pipe(pkg.babel({
    presets: [
      ['@babel/env', {
        modules: false,
      }],
    ],
  }))
  .pipe(dest(destination))

export const js = () => {
  const streams = pkg.mergeStream()
  streams.add(transpile(jsPaths.admin.src, jsPaths.admin.name, jsPaths.admin.dest))

  return streams
}

export const watchForChanges = () => {
  watch(scssPaths.watch.admin, scss)
  watch(scssPaths.watch.frontend, series(scss, css))
  watch(jsPaths.admin.src, series(js, reload))
  watch(paths.php.admin, reload)
  watch(paths.php.frontend, reload)
}

export const styles = series(scss, css) // For production run gulp styles --prod
export const dev = series(parallel(styles, js), serve, watchForChanges)

export default dev
