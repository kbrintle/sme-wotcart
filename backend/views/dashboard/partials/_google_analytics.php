<div id="embed-api-auth-container"></div>
<div class="row">
    <div class="col-xs-12 col-sm-6">
        <h4>Session</h4>
        <div id="chart-1-container"></div>
    </div>
    <div class="col-xs-12 col-sm-6">
        <h4>Pages per Session</h4>
        <div id="chart-2-container"></div>
    </div>
</div>

<div class="row">
    <div class="col-xs-12 col-sm-6">
        <h4>Average Session Duration</h4>
        <div id="chart-3-container"></div>
    </div>
    <div class="col-xs-12 col-sm-6">
        <h4>Bounce Rate</h4>
        <div id="chart-4-container"></div>
    </div>
</div>


<script>
    (function(w,d,s,g,js,fs){
        g=w.gapi||(w.gapi={});g.analytics={q:[],ready:function(f){this.q.push(f);}};
        js=d.createElement(s);fs=d.getElementsByTagName(s)[0];
        js.src='https://apis.google.com/js/platform.js';
        fs.parentNode.insertBefore(js,fs);js.onload=function(){g.load('analytics');};
    }(window,document,'script'));
</script>


<script>
    gapi.analytics.ready(function(){

        /**
         * Authorize the user immediately if the user has already granted access.
         * If no access has been created, render an authorize button inside the
         * element with the ID "embed-api-auth-container".
         */
        gapi.analytics.auth.authorize({
            'serverAuth': {
                'access_token': '<?= $access_token; ?>'
            }
        });


        /**
         * Create a new DataChart instance with the given query parameters
         * and Google chart options. It will be rendered inside an element
         * with the id "chart-X-container".
         */
        var dataChart1 = new gapi.analytics.googleCharts.DataChart({
            query: {
                ids: 'ga:<?= $ga_view; ?>',
                metrics: 'ga:sessions',
                dimensions: 'ga:date',
                'start-date': '30daysAgo',
                'end-date': 'yesterday'
            },
            chart: {
                container: 'chart-1-container',
                type: 'LINE',
                options: {
                    width: '100%'
                }
            }
        });
        var dataChart2 = new gapi.analytics.googleCharts.DataChart({
            query: {
                ids: 'ga:<?= $ga_view; ?>',
                metrics: 'ga:pageviewsPerSession',
                dimensions: 'ga:date',
                'start-date': '30daysAgo',
                'end-date': 'yesterday'
            },
            chart: {
                container: 'chart-2-container',
                type: 'LINE',
                options: {
                    width: '100%'
                }
            }
        });
        var dataChart3 = new gapi.analytics.googleCharts.DataChart({
            query: {
                ids: 'ga:<?= $ga_view; ?>',
                metrics: 'ga:avgSessionDuration',
                dimensions: 'ga:date',
                'start-date': '30daysAgo',
                'end-date': 'yesterday'
            },
            chart: {
                container: 'chart-3-container',
                type: 'LINE',
                options: {
                    width: '100%'
                }
            }
        });
        var dataChart4 = new gapi.analytics.googleCharts.DataChart({
            query: {
                ids: 'ga:<?= $ga_view; ?>',
                metrics: 'ga:bounceRate',
                dimensions: 'ga:date',
                'start-date': '30daysAgo',
                'end-date': 'yesterday'
            },
            chart: {
                container: 'chart-4-container',
                type: 'LINE',
                options: {
                    width: '100%'
                }
            }
        });
        dataChart1.execute();
        dataChart2.execute();
        dataChart3.execute();
        dataChart4.execute();

    });
</script>