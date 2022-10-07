(function (root, factory) {
    if (typeof define === 'function' && define.amd) {
        // AMD. Register as an anonymous module.
        define(['exports', 'echarts'], factory);
    } else if (typeof exports === 'object' && typeof exports.nodeName !== 'string') {
        // CommonJS
        factory(exports, require('echarts'));
    } else {
        // Browser globals
        factory({}, root.echarts);
    }
}(this, function (exports, echarts) {
    var log = function (msg) {
        if (typeof console !== 'undefined') {
            console && console.error && console.error(msg);
        }
    };
    if (!echarts) {
        log('ECharts is not Loaded');
        return;
    }
    echarts.registerTheme('shine', {
        color: [
            "#c12e34",
            "#e6b600",
            "#0098d9",
            "#2b821d",
            "#005eaa",
            "#339ca8",
            "#cda819",
            "#32a487"
        ],
        textStyle: {},
        title: {
            textStyle: {
                fontWeight: 'normal',
                color: "#516b91"
            },
            subtextStyle: {
                color: "#93b7e3"
            }
        },
        line: {
            itemStyle: {
                borderWidth: 1
            },
            lineStyle: {
                width: 2
            },
            symbolSize: 4,
            symbol: "emptyCircle",
            smooth: false
        },
        radar: {
            itemStyle: {
                borderWidth: 1
            },
            lineStyle: {
                width: 2
            },
            symbolSize: 4,
            symbol: "emptyCircle",
            smooth: false
        },
        bar: {
            itemStyle: {
                barBorderWidth: 0,
                barBorderColor: "#f1f1f1"
            }
        },
        pie: {
            itemStyle: {
                borderWidth: 0,
                borderColor: "#f1f1f1"
            }
        },
        scatter: {
            itemStyle: {
                borderWidth: 0,
                borderColor: "#f1f1f1"
            }
        },
        boxplot: {
            itemStyle: {
                borderWidth: 0,
                borderColor: "#f1f1f1"
            }
        },
        parallel: {
            itemStyle: {
                borderWidth: 0,
                borderColor: "#f1f1f1"
            }
        },
        sankey: {
            itemStyle: {
                borderWidth: 0,
                borderColor: "#f1f1f1"
            }
        },
        funnel: {
            itemStyle: {
                borderWidth: 0,
                borderColor: "#f1f1f1"
            }
        },
        gauge: {
            itemStyle: {
                borderWidth: 0,
                borderColor: "#f1f1f1"
            }
        },
        candlestick: {
            itemStyle: {
                color: "#c12e34",
                color0: "#2b821d",
                borderColor: "#c12e34",
                borderColor0: "#2b821d",
                borderWidth: 1
            }
        },
        graph: {
            itemStyle: {
                borderWidth: 0,
                borderColor: "#f1f1f1"
            },
            lineStyle: {
                width: 1,
                color: "#aaaaaa"
            },
            symbolSize: 4,
            symbol: "emptyCircle",
            smooth: false,
            color: [
                "#c12e34",
                "#e6b600",
                "#0098d9",
                "#2b821d",
                "#005eaa",
                "#339ca8",
                "#cda819",
                "#32a487"
            ],
            label: {
                color: "#eeeeee"
            }
        },
        map: {
            itemStyle: {
                normal: {
                    areaColor: "#ddd",
                    borderColor: "#eee",
                    borderWidth: 0.5
                },
                emphasis: {
                    areaColor: "#e6b600",
                    borderColor: "#ddd",
                    borderWidth: 1
                }
            },
            label: {
                normal: {
                    textStyle: {
                        color: "#c12e34"
                    }
                },
                emphasis: {
                    textStyle: {
                        color: "#c12e34"
                    }
                }
            }
        },
        geo: {
            itemStyle: {
                normal: {
                    areaColor: "#ddd",
                    borderColor: "#eee",
                    borderWidth: 0.5
                },
                emphasis: {
                    areaColor: "#e6b600",
                    borderColor: "#ddd",
                    borderWidth: 1
                }
            },
            label: {
                normal: {
                    textStyle: {
                        color: "#c12e34"
                    }
                },
                emphasis: {
                    textStyle: {
                        color: "#c12e34"
                    }
                }
            }
        },
        categoryAxis: {
            axisLine: {
                show: true,
                lineStyle: {
                    color: "#333"
                }
            },
            axisTick: {
                show: true,
                lineStyle: {
                    color: "#333"
                }
            },
            axisLabel: {
                show: true,
                textStyle: {
                    color: "#333"
                }
            },
            splitLine: {
                show: false,
                lineStyle: {
                    color: [
                        "#f1f1f1"
                    ]
                }
            },
            splitArea: {
                show: false,
                areaStyle: {
                    color: [
                        "rgba(250,250,250,0.3)",
                        "rgba(200,200,200,0.3)"
                    ]
                }
            }
        },
        valueAxis: {
            axisLine: {
                show: true,
                lineStyle: {
                    color: "#333"
                }
            },
            axisTick: {
                show: true,
                lineStyle: {
                    color: "#333"
                }
            },
            axisLabel: {
                show: true,
                textStyle: {
                    color: "#333"
                }
            },
            splitLine: {
                show: true,
                lineStyle: {
                    color: [
                        "#f1f1f1"
                    ]
                }
            },
            splitArea: {
                show: false,
                areaStyle: {
                    color: [
                        "rgba(250,250,250,0.3)",
                        "rgba(200,200,200,0.3)"
                    ]
                }
            }
        },
        logAxis: {
            axisLine: {
                show: true,
                lineStyle: {
                    color: "#333"
                }
            },
            axisTick: {
                show: true,
                lineStyle: {
                    color: "#333"
                }
            },
            axisLabel: {
                show: true,
                textStyle: {
                    color: "#333"
                }
            },
            splitLine: {
                show: true,
                lineStyle: {
                    color: [
                        "#f1f1f1"
                    ]
                }
            },
            splitArea: {
                show: false,
                areaStyle: {
                    color: [
                        "rgba(250,250,250,0.3)",
                        "rgba(200,200,200,0.3)"
                    ]
                }
            }
        },
        timeAxis: {
            axisLine: {
                show: true,
                lineStyle: {
                    color: "#333"
                }
            },
            axisTick: {
                show: true,
                lineStyle: {
                    color: "#333"
                }
            },
            axisLabel: {
                show: true,
                textStyle: {
                    color: "#333"
                }
            },
            splitLine: {
                show: true,
                lineStyle: {
                    color: [
                        "#f1f1f1"
                    ]
                }
            },
            splitArea: {
                show: false,
                areaStyle: {
                    color: [
                        "rgba(250,250,250,0.3)",
                        "rgba(200,200,200,0.3)"
                    ]
                }
            }
        },
        toolbox: {
            iconStyle: {
                normal: {
                    borderColor: "#06467c"
                },
                emphasis: {
                    borderColor: "#4187c2"
                }
            }
        },
        legend: {
            textStyle: {
                color: "#333333"
            }
        },
        tooltip: {
            backgroundColor: 'rgba(50,50,50,0.5)',
            axisPointer: {
                lineStyle: {
                    color: "#27727b",
                    width: 1
                },
                crossStyle: {
                    color: "#27727b",
                    width: 1
                }
            }
        },
        timeline: {
            lineStyle: {
                color: "#005eaa",
                width: 1
            },
            itemStyle: {
                normal: {
                    color: "#005eaa",
                    borderWidth: 1
                },
                emphasis: {
                    color: "#005eaa"
                }
            },
            controlStyle: {
                normal: {
                    color: "#005eaa",
                    borderColor: "#005eaa",
                    borderWidth: 0.5
                },
                emphasis: {
                    color: "#005eaa",
                    borderColor: "#005eaa",
                    borderWidth: 0.5
                }
            },
            checkpointStyle: {
                color: "#005eaa",
                borderColor: "rgba(49,107,194,0.5)"
            },
            label: {
                normal: {
                    textStyle: {
                        color: "#005eaa"
                    }
                },
                emphasis: {
                    textStyle: {
                        color: "#005eaa"
                    }
                }
            }
        },
        visualMap: {
            color: [
                "#1790cf",
                "#a2d4e6"
            ]
        },
        dataZoom: {
            backgroundColor: "rgba(47,69,84,0)",
            dataBackgroundColor: "rgba(47,69,84,0.3)",
            fillerColor: "rgba(167,183,204,0.4)",
            handleColor: "#a7b7cc",
            handleSize: "100%",
            textStyle: {
                color: "#333333"
            }
        },
        markPoint: {
            label: {
                color: "#eeeeee"
            },
            emphasis: {
                label: {
                    color: "#eeeeee"
                }
            }
        }
    });
}));
