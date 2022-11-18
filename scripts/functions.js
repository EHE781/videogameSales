/*FIRST CHART */
function pieChart() {
    $.ajax({
        async: false,
        url: "scripts/data.php",
        type: 'GET',
        data: {
            choice: 'pie'
        },
        cache: true,
        success: function (query) {
            console.log('Ajax executed the call successfully')
        },
        complete: function (response) {
            var jsonData = response.responseText;
            const result = jQuery.parseJSON(jsonData)
            var dataArray = [];
            for (var i = 0; i < result.length; i++) {
                dataArray.push({ "name": result[i].Publisher, "y": parseFloat(result[i].Global_Sales) })
            }
            Highcharts.chart('pieContainer', {
                chart: {
                    plotBackgroundColor: null,
                    plotBorderWidth: null,
                    plotShadow: false,
                    type: 'pie'
                },
                title: {
                    text: 'Top 20 Publishers of all time'
                },
                tooltip: {
                    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                },
                accessibility: {
                    point: {
                        valueSuffix: '%'
                    }
                },
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        dataLabels: {
                            enabled: true,
                            format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                        }
                    }
                },

                series: [{
                    colorByPoint: true,
                    data: dataArray,
                }]
            });
        },
        error: function (req, err) { console.log('Ajax received this error: ' + err); }
    });
}
/*SECOND CHART */
function raceChart() {
    //Initial constants, variables and declaration
    const startYear = 1980,
        endYear = 2020,
        btn = document.getElementById("play-pause-button"),
        input = document.getElementById("play-range"),
        nbr = 20;
    let dataset, chart;
    dataset = [];


    /*
     * Animate dataLabels functionality
     */
    (function (H) {
        const FLOAT = /^-?\d+\.?\d*$/;

        // Add animated textSetter, just like fill/strokeSetters
        H.Fx.prototype.textSetter = function () {
            let startValue = this.start.replace(/ /g, ""),
                endValue = this.end.replace(/ /g, ""),
                currentValue = this.end.replace(/ /g, "");

            if ((startValue || "").match(FLOAT)) {
                startValue = parseInt(startValue, 10);
                endValue = parseInt(endValue, 10);

                // No support for float
                currentValue = Highcharts.numberFormat(
                    Math.round(startValue + (endValue - startValue) * this.pos),
                    0
                );
            }

            this.elem.endText = this.end;

            this.elem.attr(this.prop, currentValue, null, true);
        };

        // Add textGetter, not supported at all at this moment:
        H.SVGElement.prototype.textGetter = function () {
            const ct = this.text.element.textContent || "";
            return this.endText ? this.endText : ct.substring(0, ct.length / 2);
        };

        // Temporary change label.attr() with label.animate():
        // In core it's simple change attr(...) => animate(...) for text prop
        H.wrap(H.Series.prototype, "drawDataLabels", function (proceed) {
            const attr = H.SVGElement.prototype.attr,
                chart = this.chart;

            if (chart.sequenceTimer) {
                this.points.forEach(point =>
                    (point.dataLabels || []).forEach(
                        label =>
                        (label.attr = function (hash) {
                            if (hash && hash.text !== undefined) {
                                const text = hash.text;

                                delete hash.text;

                                return this
                                    .attr(hash)
                                    .animate({ text });
                            }
                            return attr.apply(this, arguments);

                        })
                    )
                );
            }

            const ret = proceed.apply(
                this,
                Array.prototype.slice.call(arguments, 1)
            );

            this.points.forEach(p =>
                (p.dataLabels || []).forEach(d => (d.attr = attr))
            );

            return ret;
        });
    }(Highcharts));

    /*
    * Get the data for the chart
    */
    function getData(year) {
        const yearDataset = dataset[year - 1980]

        const output = Object.entries(yearDataset)
            .map(function (yearData) {
                return [yearData[1].Name, Number(yearData[1].Global_Sales)]
            })
            .sort((a, b) => b[1] - a[1]);
        return [output[0], output.slice(1, nbr)];
    }

    function getSubtitle() {
        const totalSales = (getData(input.value)[0][1]).toFixed(2);
        return `<span style="font-size: 80px">${input.value}</span>
        <br>
        <span style="font-size: 22px">
            Total: <b>: ${totalSales}</b> million sold
        </span>`;
    }

    (async () => {
        $.ajax({
            async: false,
            url: "scripts/data.php",
            type: 'GET',
            data: {
                choice: 'race',
                year: 1980
            },
            cache: true,
            success: function (query) {
                console.log('Ajax executed the call successfully (race)')
            },
            complete: function (response) {
                var jsonData = response.responseText;
                const result = jQuery.parseJSON(jsonData)
                dataset = result


                chart = Highcharts.chart('raceContainer', {
                    chart: {
                        animation: {
                            duration: 500
                        },
                        marginRight: 50,
                    },
                    title: {
                        text: 'Videogames by Global Sales',
                        align: 'left'
                    },
                    subtitle: {
                        useHTML: true,
                        text: getSubtitle(),
                        floating: true,
                        align: 'right',
                        verticalAlign: 'middle',
                        y: -20,
                        x: -100
                    },

                    legend: {
                        enabled: false
                    },
                    xAxis: {
                        type: "category"
                    },
                    yAxis: {
                        opposite: true,
                        tickPixelInterval: 150,
                        title: {
                            text: null
                        }
                    },
                    plotOptions: {
                        series: {
                            animation: false,
                            groupPadding: 0,
                            pointPadding: 0.1,
                            borderWidth: 0,
                            colorByPoint: true,
                            dataSorting: {
                                enabled: true,
                                matchByName: true
                            },
                            type: "bar",
                            dataLabels: {
                                enabled: true
                            }
                        }
                    },
                    series: [
                        {
                            type: 'bar',
                            name: startYear,
                            data: getData(startYear)[1],

                        }
                    ],
                    responsive: {
                        rules: [{
                            condition: {
                                maxWidth: 550
                            },
                            chartOptions: {
                                xAxis: {
                                    visible: false
                                },
                                subtitle: {
                                    x: 0
                                },
                                plotOptions: {
                                    series: {
                                        dataLabels: [{
                                            enabled: true,
                                            y: 8
                                        }, {
                                            enabled: true,
                                            format: '{point.name}',
                                            y: -8,
                                            style: {
                                                fontWeight: 'normal',
                                                opacity: 0.7
                                            }
                                        }]
                                    }
                                }
                            }
                        }]
                    }
                })
            }


        })
    })();

    /*
     * Pause the timeline, either when the range is ended, or when clicking the pause button.
     * Pausing stops the timer and resets the button to play mode.
     */
    function pause(button) {
        button.title = "play";
        button.className = "fa fa-play";
        clearTimeout(chart.sequenceTimer);
        chart.sequenceTimer = undefined;
    }

    /*
     * Update the chart. This happens either on updating (moving) the range input,
     * or from a timer when the timeline is playing.
     */
    function update(increment) {
        if (increment) {
            input.value = parseInt(input.value, 10) + increment;
        }
        if (input.value >= endYear) {
            // Auto-pause
            pause(btn);
        }

        chart.update(
            {
                subtitle: {
                    text: getSubtitle(dataset)
                }
            },
            false,
            false,
            false
        );

        chart.series[0].update({
            name: input.value,
            data: getData(input.value, dataset)[1]
        });
    }

    /*
     * Play the timeline.
     */
    function play(button) {
        button.title = "pause";
        button.className = "fa fa-pause";
        chart.sequenceTimer = setInterval(function () {
            update(1);
        }, 500);
    }

    btn.addEventListener("click", function () {
        if (chart.sequenceTimer) {
            pause(this);
        } else {
            play(this);
        }
    });
    /*
     * Trigger the update on the range bar click.
     */
    input.addEventListener("click", function () {
        update();
    });
}
/* THIRD CHART */
function donutChart() {
    $.ajax({
        async: false,
        url: "scripts/data.php",
        type: 'GET',
        data: {
            choice: 'donut'
        },
        cache: true,
        success: function (query) {
            console.log('Ajax executed the call successfully')
        },
        complete: function (response) {
            var jsonData = response.responseText;
            const result = jQuery.parseJSON(jsonData)
            var calculateTotal = Object.entries(result).map(function (entry) {
                return entry[1].length
            })
            var totalGames = calculateTotal.reduce((partialSum, a) => partialSum + a, 0);
            var datos = Object.entries(result).map(function (entry) {
                return {
                    name: entry[0],
                    y: (entry[1].length / totalGames),
                    dataLabels: {
                        enabled: true
                    },
                }
            })
            var chart = Highcharts.chart('donutContainer', {
                chart: {
                    plotBackgroundColor: null,
                    plotBorderWidth: 0,
                    plotShadow: false
                },
                title: {
                    text: 'Genres<br>by<br>popularity',
                    align: 'center',
                    verticalAlign: 'middle',
                    y: 60
                },
                tooltip: {
                    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                },
                accessibility: {
                    point: {
                        valueSuffix: '%'
                    }
                },
                plotOptions: {
                    pie: {
                        dataLabels: {
                            enabled: true,
                            distance: -50,
                            style: {
                                fontWeight: 'bold',
                                color: 'white'
                            }
                        },
                        startAngle: -90,
                        endAngle: 90,
                        center: ['50%', '75%'],
                        size: '110%'
                    }
                },
                series: [{
                    type: 'pie',
                    name: 'Quantity of games',
                    innerSize: '50%',
                    data: datos
                }]
            });
        },
        error: function (req, err) { console.log('Ajax received this error: ' + err); }
    });
}

/* */