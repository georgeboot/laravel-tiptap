import babel from '@rollup/plugin-babel'
import typescript from 'rollup-plugin-typescript2'
import commonjs from '@rollup/plugin-commonjs'

export default {
    input: './packages/laravel-tiptap/src/index.ts',
    output: [
        {
            name: 'laravel-tiptap',
            file: './packages/laravel-tiptap/dist/laravel-tiptap.umd.js',
            format: 'umd',
            sourcemap: true,
        },
        {
            name: 'laravel-tiptap',
            file: './packages/laravel-tiptap/dist/laravel-tiptap.cjs.js',
            format: 'cjs',
            sourcemap: true,
            exports: 'auto',
        },
        {
            name: 'laravel-tiptap',
            file: './packages/laravel-tiptap/dist/laravel-tiptap.esm.js',
            format: 'es',
            sourcemap: true,
        },
    ],
    plugins: [
        typescript({
            tsconfigOverride: {
                compilerOptions: {
                    declaration: true,
                    paths: {
                        'laravel-tiptap': ['packages/*/src'],
                    },
                },
                include: null,
            },
        }),
        babel({
            babelHelpers: 'bundled',
            exclude: 'node_modules/**',
        }),
        commonjs(),
    ],
}
