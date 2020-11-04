var ExtractTextPlugin = require("extract-text-webpack-plugin");
module.exports = {
    entry: './src/css/admin.css',
    output: {
        path: './dist',
        filename: 'theme.css'
    },
    module: {
        loaders: [
            {
                test: /\.css/,
                loader: ExtractTextPlugin.extract("style-loader", "css-loader")
            }, {
                test: /\.(png|jpg|svg)$/,
                loader: 'url-loader'
            }
        ]
    },
    plugins: [
        new ExtractTextPlugin('theme.css')
    ]
};