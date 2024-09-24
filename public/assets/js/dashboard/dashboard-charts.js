var csrfToken = $('meta[name="csrf-token"]').attr('content');
let formatter = new Intl.NumberFormat('en-US', {
  style: 'decimal',
  minimumFractionDigits: 3,
  maximumFractionDigits: 3
});
jQuery(function () {
    getMonthPurchaseCost();
    getTyreCPKCharts();

    $('#filterYear').on('change', function () {
        console.log("hola");
        getMonthPurchaseCost();
        getTyreCPKCharts();
    });

});

function getMonthPurchaseCost() {
    $.ajax({
        url: '/dashboard-charts/getCharts',
        type: 'GET',
        headers: {
            'X-CSRF-TOKEN': csrfToken
        },
        data: {
            filterYear: $('#filterYear').val(),
        },
        success: function (response, status, xhr) {
            console.log(response);
            populateUnitTyres(response['unitTyres']);
            populateCostTyres(response["costTyres"]);
            populateUnitPurchaseTyres(response['unitPurchaseTyres']);
            populateCostPurchaseTyres(response['costPurchaseTyres']);
            populatetyrePerStatus(response['tyrePerStatus']);
        },
        error: function (xhr, status, error) {
            console.log(xhr);
        }
    });
}

function getTyreCPKCharts() {
    $.ajax({
        url: '/dashboard-charts/getTyreCPKCharts',
        type: 'GET',
        headers: {
            'X-CSRF-TOKEN': csrfToken
        },
        data: {
            filterYear: $('#filterYear').val(),
        },
        success: function (response, status, xhr) {
            console.log(response);
            populateCPKTyres(response['data']);
        },
        error: function (xhr, status, error) {
            console.log(xhr);
        }
    });
}

function populateCPKTyres(data) {

    const months = ['BOGIE', 'DRIVE', 'STEER', 'TRAILER'];

    const seriesData = {
        NEW: { bar: [] },
        COC: { bar: [] },
        RETREAD: { bar: [] },
        USED: { bar: [] }
    };

    let min_tyre_cost_km = Infinity, max_tyre_cost_km = -Infinity;

    months.forEach(() => {
        seriesData.NEW.bar.push(0);
        seriesData.COC.bar.push(0);
        seriesData.RETREAD.bar.push(0);
        seriesData.USED.bar.push(0);
    });

    data.forEach(item => {
        const monthIndex = item.month - 1; // Adjust for zero-based index
        if (item.tyre_type in seriesData) {

            switch (item.axle_type) {
                case 'BOGIE':
                    index = 0;
                    break;
                case 'DRIVE':
                    index = 1;
                    break;

                case 'STEER':
                    index = 2;
                    break;

                case 'TRAILER':
                    index = 3;
                    break;
            }
            seriesData[item.tyre_type].bar[index] = item.tyre_cost_km / item.number;
        }

        // Update min/max values for tyre_count
        if (item.tyre_cost_km < min_tyre_cost_km) min_tyre_cost_km = item.tyre_cost_km;
        if (item.tyre_cost_km > max_tyre_cost_km) max_tyre_cost_km = item.tyre_cost_km;

    });

    console.log(months);
    console.log(seriesData);

  const option = {
      tooltip: {
          trigger: 'axis',
          axisPointer: {
              type: 'cross'
          },
          formatter: function (params) {
              let category = params[0].name;
              let tooltipText = `<strong>${category}</strong><br>`;
              params.forEach(item => {
                  if (item.seriesType === 'bar') {
                    tooltipText += `${item.marker} ${item.seriesName} - RM${formatter.format(item.value)}/Km<br>`;
                  }
              });
              return tooltipText;
          }
      },
      legend: {
          data: ['NEW', 'COC', 'RETREAD', 'USED'] // Same legend for bars and lines
      },
      toolbox: {
          show: false,
          orient: 'vertical',
          left: 'right',
          top: 'center',
          feature: {
              mark: { show: true },
              dataView: { show: true, readOnly: false },
              magicType: { show: true, type: ['line', 'bar', 'stack'] },
              restore: { show: true },
              saveAsImage: { show: true }
          }
      },
      xAxis: [
          {
              type: 'category',
              axisTick: { show: false },
              data: months,
              axisLabel: {
                  interval: 0,  // Ensure all labels are shown
                  rotate: 0,    // Adjust rotation if necessary
                  showMaxLabel: true, // Ensure the last label is displayed
                  showMinLabel: true  // Ensure the first label is displayed
              }
          }
      ],
      yAxis: [
          {
              type: 'value',
              name: 'CPK (RM)',
              position: 'left',
              axisLine: {
                  lineStyle: {
                      color: '#5470C6'
                  }
              },
              axisLabel: {
                  formatter: '{value}'
              },
              min: 0,
              max: max_tyre_cost_km
          }
      ],
      series: [
          {
              name: 'NEW',
              type: 'bar',
              barGap: 0,
              emphasis: {
                  focus: 'series'
              },
              data: seriesData.NEW.bar
          },
          {
              name: 'COC',
              type: 'bar',
              emphasis: {
                  focus: 'series'
              },
              data: seriesData.COC.bar
          },
          {
              name: 'RETREAD',
              type: 'bar',
              emphasis: {
                  focus: 'series'
              },
              data: seriesData.RETREAD.bar
          },
          {
              name: 'USED',
              type: 'bar',
              emphasis: {
                  focus: 'series'
              },
              data: seriesData.USED.bar
          },
      ]
  };

    // Initialize or update the chart with the new options
    const chart = echarts.init(document.getElementById('cpk-tyres-chart'));
    chart.setOption(option);
}

function populateUnitTyres(data) {

    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
        'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];



    const seriesData = {
        NEW: { bar: [] },
        COC: { bar: [] },
        RETREAD: { bar: [] },
        USED: { bar: [] }
    };

    let minTyreCount = Infinity, maxTyreCount = -Infinity;

    months.forEach(() => {
        seriesData.NEW.bar.push(0);
        seriesData.COC.bar.push(0);
        seriesData.RETREAD.bar.push(0);
        seriesData.USED.bar.push(0);
    });

    data.forEach(item => {
        const monthIndex = item.month - 1; // Adjust for zero-based index
        if (item.tyre_type in seriesData) {
            seriesData[item.tyre_type].bar[monthIndex] = item.tyre_count;
        }

        // Update min/max values for tyre_count
        if (item.tyre_count < minTyreCount) minTyreCount = item.tyre_count;
        if (item.tyre_count > maxTyreCount) maxTyreCount = item.tyre_count;

    });

    console.log(months);
    console.log(seriesData);

    const option = {
        tooltip: {
            trigger: 'axis',
            axisPointer: {
                type: 'cross'
            },
            formatter: function (params) {
                let category = params[0].name;
                let tooltipText = `<strong>${category}</strong><br>`;
                params.forEach(item => {
                    if (item.seriesType === 'bar') {
                        tooltipText += `${item.marker} ${item.seriesName} - ${item.value}<br>`;
                    } else if (item.seriesType === 'line') {
                        tooltipText += `${item.marker} ${item.seriesName} - RM${item.value}<br>`;
                    }
                });
                return tooltipText;
            }
        },
        legend: {
            data: ['NEW', 'COC', 'RETREAD', 'USED'] // Same legend for bars and lines
        },
        toolbox: {
            show: false,
            orient: 'vertical',
            left: 'right',
            top: 'center',
            feature: {
                mark: { show: true },
                dataView: { show: true, readOnly: false },
                magicType: { show: true, type: ['line', 'bar', 'stack'] },
                restore: { show: true },
                saveAsImage: { show: true }
            }
        },
        xAxis: [
            {
                type: 'category',
                axisTick: { show: false },
                data: months,
                axisLabel: {
                    interval: 0,  // Ensure all labels are shown
                    rotate: 0,    // Adjust rotation if necessary
                    showMaxLabel: true, // Ensure the last label is displayed
                    showMinLabel: true  // Ensure the first label is displayed
                }
            }
        ],
        yAxis: [
            {
                type: 'value',
                name: 'No. of Tyres',
                position: 'left',
                axisLine: {
                    lineStyle: {
                        color: '#5470C6'
                    }
                },
                axisLabel: {
                    formatter: '{value}'
                },
                min: 0,
                max: maxTyreCount
            }
        ],
        series: [
            {
                name: 'NEW',
                type: 'bar',
                barGap: 0,
                emphasis: {
                    focus: 'series'
                },
                data: seriesData.NEW.bar
            },
            {
                name: 'COC',
                type: 'bar',
                emphasis: {
                    focus: 'series'
                },
                data: seriesData.COC.bar
            },
            {
                name: 'RETREAD',
                type: 'bar',
                emphasis: {
                    focus: 'series'
                },
                data: seriesData.RETREAD.bar
            },
            {
                name: 'USED',
                type: 'bar',
                emphasis: {
                    focus: 'series'
                },
                data: seriesData.USED.bar
            },
        ]
    };

    // Initialize or update the chart with the new options
    const chart = echarts.init(document.getElementById('unit-tyres-chart'));
    chart.setOption(option);
}

function populateCostTyres(data) {
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
        'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

    const seriesData = {
        NEW: { bar: [], line: [] },
        COC: { bar: [], line: [] },
        RETREAD: { bar: [], line: [] },
        USED: { bar: [], line: [] }
    };

    let minTyrePrice = Infinity, maxTyrePrice = -Infinity;
    let minTyreCount = Infinity, maxTyreCount = -Infinity;

    months.forEach(() => {
        seriesData.NEW.bar.push(0);
        seriesData.COC.bar.push(0);
        seriesData.RETREAD.bar.push(0);
        seriesData.USED.bar.push(0);
        seriesData.NEW.line.push(0);
        seriesData.COC.line.push(0);
        seriesData.RETREAD.line.push(0);
        seriesData.USED.line.push(0);
    });

    data.forEach(item => {
        const monthIndex = item.month - 1; // Adjust for zero-based index
        if (item.tyre_type in seriesData) {
            seriesData[item.tyre_type].bar[monthIndex] = item.tyre_count;
            seriesData[item.tyre_type].line[monthIndex] = item.total_price;
        }

        // Update min/max values for tyre_count
        if (item.tyre_count < minTyreCount) minTyreCount = item.tyre_count;
        if (item.tyre_count > maxTyreCount) maxTyreCount = item.tyre_count;

        const price = parseFloat(item.total_price);

        // Update min/max values for tyre_price
        if (price < minTyrePrice) minTyrePrice = price;
        if (price > maxTyrePrice) maxTyrePrice = price;

    });

    const option = {
        tooltip: {
            trigger: 'axis',
            axisPointer: {
                type: 'cross'
            },
            formatter: function (params) {
                let category = params[0].name;
                let tooltipText = `<strong>${category}</strong><br>`;
                let formatter = new Intl.NumberFormat('en-US', {
                  style: 'decimal',
                  minimumFractionDigits: 2,
                  maximumFractionDigits: 2
                });
                params.forEach(item => {
                  if (item.seriesType === 'bar') {
                    tooltipText += `${item.marker} ${item.seriesName} - ${item.value}<br>`;
                  } else if (item.seriesType === 'line') {
                      tooltipText += `${item.marker} ${item.seriesName} - RM${formatter.format(item.value)}<br>`;
                  }
                });
                return tooltipText;
            }
        },
        legend: {
            data: ['NEW', 'COC', 'RETREAD', 'USED'] // Same legend for bars and lines
        },
        toolbox: {
            show: false,
            orient: 'vertical',
            left: 'right',
            top: 'center',
            feature: {
                mark: { show: true },
                dataView: { show: true, readOnly: false },
                magicType: { show: true, type: ['line', 'bar', 'stack'] },
                restore: { show: true },
                saveAsImage: { show: true }
            }
        },
        xAxis: [
            {
                type: 'category',
                axisTick: { show: false },
                data: months,
                axisLabel: {
                    interval: 0,  // Ensure all labels are shown
                    rotate: 0,    // Adjust rotation if necessary
                    showMaxLabel: true, // Ensure the last label is displayed
                    showMinLabel: true  // Ensure the first label is displayed
                }
            }
        ],
        yAxis: [
            {
                type: 'value',
                name: 'Tyre Cost (RM)',
                position: 'left',
                axisLine: {
                    lineStyle: {
                        // color: '#91CC75'
                    }
                },
                axisLabel: {
                    formatter: '{value}'
                },
                min: 0,
                max: maxTyrePrice
            }
        ],
        series: [
            // Line series for 'NEW'
            {
                name: 'NEW',
                type: 'line',
                yAxisIndex: 0,
                emphasis: {
                    focus: 'series'
                },
                data: seriesData.NEW.line,
                //   smooth: true // Optional: smooth the line
            },
            // Line series for 'COC'
            {
                name: 'COC',
                type: 'line',
                yAxisIndex: 0,
                emphasis: {
                    focus: 'series'
                },
                data: seriesData.COC.line,
                //   smooth: true // Optional: smooth the line
            },
            // Line series for 'RETREAD'
            {
                name: 'RETREAD',
                type: 'line',
                yAxisIndex: 0,
                emphasis: {
                    focus: 'series'
                },
                data: seriesData.RETREAD.line,
                //   smooth: true // Optional: smooth the line
            },
            // Line series for 'USED'
            {
                name: 'USED',
                type: 'line',
                yAxisIndex: 0,
                emphasis: {
                    focus: 'series'
                },
                data: seriesData.USED.line,
                //   smooth: true // Optional: smooth the line
            }
        ]
    };

    // Initialize or update the chart with the new options
    const chart = echarts.init(document.getElementById('cost-tyres-chart'));
    chart.setOption(option);
}

function populateUnitPurchaseTyres(data) {

    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
        'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];



    const seriesData = {
        NEW: { bar: [] },
        COC: { bar: [] },
        RETREAD: { bar: [] },
        USED: { bar: [] }
    };

    let minTyreCount = Infinity, maxTyreCount = -Infinity;

    months.forEach(() => {
        seriesData.NEW.bar.push(0);
        seriesData.COC.bar.push(0);
        seriesData.RETREAD.bar.push(0);
        seriesData.USED.bar.push(0);
    });

    data.forEach(item => {
        const monthIndex = item.month - 1; // Adjust for zero-based index
        if (item.tyre_type in seriesData) {
            seriesData[item.tyre_type].bar[monthIndex] = item.tyre_count;
        }

        // Update min/max values for tyre_count
        if (item.tyre_count < minTyreCount) minTyreCount = item.tyre_count;
        if (item.tyre_count > maxTyreCount) maxTyreCount = item.tyre_count;

    });

    console.log(months);
    console.log(seriesData);

    const option = {
        tooltip: {
            trigger: 'axis',
            axisPointer: {
                type: 'cross'
            },
            formatter: function (params) {
                let category = params[0].name;
                let tooltipText = `<strong>${category}</strong><br>`;
                params.forEach(item => {
                  if (item.seriesType === 'bar') {
                      tooltipText += `${item.marker} ${item.seriesName} - ${item.value}<br>`;
                  } else if (item.seriesType === 'line') {
                      tooltipText += `${item.marker} ${item.seriesName} - RM${item.value}<br>`;
                  }
                });
                return tooltipText;
            }
        },
        legend: {
            data: ['NEW', 'COC', 'RETREAD', 'USED'] // Same legend for bars and lines
        },
        toolbox: {
            show: false,
            orient: 'vertical',
            left: 'right',
            top: 'center',
            feature: {
                mark: { show: true },
                dataView: { show: true, readOnly: false },
                magicType: { show: true, type: ['line', 'bar', 'stack'] },
                restore: { show: true },
                saveAsImage: { show: true }
            }
        },
        xAxis: [
            {
                type: 'category',
                axisTick: { show: false },
                data: months,
                axisLabel: {
                    interval: 0,  // Ensure all labels are shown
                    rotate: 0,    // Adjust rotation if necessary
                    showMaxLabel: true, // Ensure the last label is displayed
                    showMinLabel: true  // Ensure the first label is displayed
                }
            }
        ],
        yAxis: [
            {
                type: 'value',
                name: 'No. of Tyres',
                position: 'left',
                axisLine: {
                    lineStyle: {
                        color: '#5470C6'
                    }
                },
                axisLabel: {
                    formatter: '{value}'
                },
                min: 0,
                max: maxTyreCount
            }
        ],
        series: [
            {
                name: 'NEW',
                type: 'bar',
                barGap: 0,
                emphasis: {
                    focus: 'series'
                },
                data: seriesData.NEW.bar
            },
            {
                name: 'COC',
                type: 'bar',
                emphasis: {
                    focus: 'series'
                },
                data: seriesData.COC.bar
            },
            {
                name: 'RETREAD',
                type: 'bar',
                emphasis: {
                    focus: 'series'
                },
                data: seriesData.RETREAD.bar
            },
            {
                name: 'USED',
                type: 'bar',
                emphasis: {
                    focus: 'series'
                },
                data: seriesData.USED.bar
            },
        ]
    };

    // Initialize or update the chart with the new options
    const chart = echarts.init(document.getElementById('unit-installed-tyres-chart'));
    chart.setOption(option);
}

function populateCostPurchaseTyres(data) {
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
        'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

    const seriesData = {
        NEW: { bar: [], line: [] },
        COC: { bar: [], line: [] },
        RETREAD: { bar: [], line: [] },
        USED: { bar: [], line: [] }
    };

    let minTyrePrice = Infinity, maxTyrePrice = -Infinity;
    let minTyreCount = Infinity, maxTyreCount = -Infinity;

    months.forEach(() => {
        seriesData.NEW.bar.push(0);
        seriesData.COC.bar.push(0);
        seriesData.RETREAD.bar.push(0);
        seriesData.USED.bar.push(0);
        seriesData.NEW.line.push(0);
        seriesData.COC.line.push(0);
        seriesData.RETREAD.line.push(0);
        seriesData.USED.line.push(0);
    });

    data.forEach(item => {
        const monthIndex = item.month - 1; // Adjust for zero-based index
        if (item.tyre_type in seriesData) {
            seriesData[item.tyre_type].bar[monthIndex] = item.tyre_count;
            seriesData[item.tyre_type].line[monthIndex] = item.total_price;
        }

        // Update min/max values for tyre_count
        if (item.tyre_count < minTyreCount) minTyreCount = item.tyre_count;
        if (item.tyre_count > maxTyreCount) maxTyreCount = item.tyre_count;

        const price = parseFloat(item.total_price);

        // Update min/max values for tyre_price
        if (price < minTyrePrice) minTyrePrice = price;
        if (price > maxTyrePrice) maxTyrePrice = price;

    });

    const option = {
        tooltip: {
            trigger: 'axis',
            axisPointer: {
                type: 'cross'
            },
            formatter: function (params) {
                let category = params[0].name;
                let tooltipText = `<strong>${category}</strong><br>`;
                let formatter = new Intl.NumberFormat('en-US', {
                  style: 'decimal',
                  minimumFractionDigits: 2,
                  maximumFractionDigits: 2
                });
                params.forEach(item => {
                  if (item.seriesType === 'bar') {
                      tooltipText += `${item.marker} ${item.seriesName} - ${item.value}<br>`;
                  } else if (item.seriesType === 'line') {
                      tooltipText += `${item.marker} ${item.seriesName} - RM${formatter.format(item.value)}<br>`;
                  }
                });
                return tooltipText;
            }
        },
        legend: {
            data: ['NEW', 'COC', 'RETREAD', 'USED'] // Same legend for bars and lines
        },
        toolbox: {
            show: false,
            orient: 'vertical',
            left: 'right',
            top: 'center',
            feature: {
                mark: { show: true },
                dataView: { show: true, readOnly: false },
                magicType: { show: true, type: ['line', 'bar', 'stack'] },
                restore: { show: true },
                saveAsImage: { show: true }
            }
        },
        xAxis: [
            {
                type: 'category',
                axisTick: { show: false },
                data: months,
                axisLabel: {
                    interval: 0,  // Ensure all labels are shown
                    rotate: 0,    // Adjust rotation if necessary
                    showMaxLabel: true, // Ensure the last label is displayed
                    showMinLabel: true  // Ensure the first label is displayed
                }
            }
        ],
        yAxis: [
            {
                type: 'value',
                name: 'Tyre Cost (RM)',
                position: 'left',
                axisLine: {
                    lineStyle: {
                        // color: '#91CC75'
                    }
                },
                axisLabel: {
                    formatter: '{value}'
                },
                min: 0,
                max: maxTyrePrice
            }
        ],
        series: [
            // Line series for 'NEW'
            {
                name: 'NEW',
                type: 'line',
                yAxisIndex: 0,
                emphasis: {
                    focus: 'series'
                },
                data: seriesData.NEW.line,
                //   smooth: true // Optional: smooth the line
            },
            // Line series for 'COC'
            {
                name: 'COC',
                type: 'line',
                yAxisIndex: 0,
                emphasis: {
                    focus: 'series'
                },
                data: seriesData.COC.line,
                //   smooth: true // Optional: smooth the line
            },
            // Line series for 'RETREAD'
            {
                name: 'RETREAD',
                type: 'line',
                yAxisIndex: 0,
                emphasis: {
                    focus: 'series'
                },
                data: seriesData.RETREAD.line,
                //   smooth: true // Optional: smooth the line
            },
            // Line series for 'USED'
            {
                name: 'USED',
                type: 'line',
                yAxisIndex: 0,
                emphasis: {
                    focus: 'series'
                },
                data: seriesData.USED.line,
                //   smooth: true // Optional: smooth the line
            }
        ]
    };

    // Initialize or update the chart with the new options
    const chart = echarts.init(document.getElementById('cost-installed-tyres-chart'));
    chart.setOption(option);
}

function populatetyrePerStatus(data) {
    console.log(data);

    // Transform the data to fit ECharts format
    const transformedData = data.map(item => ({
        value: item.tyre_count,
        name: item.tyre_status
    }));

    // ECharts option
    const option = {
        tooltip: {
            trigger: 'item',
            formatter: function (params) {
                // Format the value with thousand separators
                return `${params.name} (${params.value.toLocaleString()} Unit)`;
            }
        },
        legend: {
            bottom: 10,
            left: 'center',
            // padding: 0,  // Remove padding around legend
        },
        series: [
            {
                type: 'pie',
                radius: '75%',
                data: transformedData, // Use the transformed data here
                emphasis: {
                    itemStyle: {
                        shadowBlur: 10,
                        shadowOffsetX: 0,
                        shadowColor: 'rgba(0, 0, 0, 0.5)'
                    }
                },
                label: {
                    show: true,
                    position: 'inside',
                    formatter: '{d}%' // Display the percentage
                }
            }
        ]
    };


    // Initialize or update the chart with the new options
    const chart = echarts.init(document.getElementById('tyre-per-status-chart'));
    chart.setOption(option);

}

