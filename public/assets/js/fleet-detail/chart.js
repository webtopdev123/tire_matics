var csrfToken = $('meta[name="csrf-token"]').attr('content');

jQuery(function () {
    getTyreCPKCharts();
});

function getChartData(tyre_id) {
  $.ajax({
      url: '/fleet-detail/getcharts',
      type: 'POST',
      data: {
        'tyre_id': tyre_id,
        'fleet_id': $('#fleet_id').val(),
      },
      headers: {
          'X-CSRF-TOKEN': csrfToken
      },
      success: function (response, status, xhr) {
          console.log(response);
          populateProjectionRecords(response['outputs']);
          populateCpkRecords(response['outputs']);


      },
      error: function (xhr, status, error) {
          console.log(xhr);
      }
  });

}

function getTyreCPKCharts() {
  $.ajax({
      url: '/dashboard-charts/getTyreCPKCharts?fleet_id=' + $('#fleet_id').val(),
      type: 'GET',
      headers: {
          'X-CSRF-TOKEN': csrfToken
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


function populateProjectionRecords(data) {
  const months = ['1', '2', '3', '4', '5', '6',
    '7', '8', '9', '10', '11', '12'];

  const text_months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
      'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

  const seriesData = {
      NEW: {line: [] },
  };

  let minProjection = Infinity, maxProjection = -Infinity;

  months.forEach(() => {
      seriesData.NEW.line.push(0);
  });

  data.forEach(item => {
      const monthIndex = item.month - 1; // Adjust for zero-based index
      seriesData['NEW'].line[monthIndex] = item.tyre_projection;

      const tyre_projection = parseFloat(item.tyre_projection);

      // Update min/max values for tyre_price
      if (tyre_projection < minProjection) minProjection = tyre_projection;
      if (tyre_projection > maxProjection) maxProjection = tyre_projection;

  });

  const option = {
      tooltip: {
          trigger: 'axis',
          axisPointer: {
              type: 'cross'
          },
          formatter: function (params) {
              let category = params[0].name;
              index = parseFloat(category) - 1;
              let tooltipText = `<strong>${text_months[index]}</strong><br>`;
              let formatter = new Intl.NumberFormat('en-US', {
                style: 'decimal',
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
              });
              console.log('test');
              console.log(params);
              params.forEach(item => {
                  if (item.seriesType === 'line') {
                      tooltipText += `${item.marker} ${formatter.format(item.value)}KM<br>`;
                  }
              });
              return tooltipText;
          }
      },
      legend: {
          data: ['NEW'] // Same legend for bars and lines
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
              name: 'KM',
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
              max: maxProjection
          }
      ],
      series: [
          // Line series for 'NEW'
          {
              name: 'Projection Data',
              type: 'line',
              yAxisIndex: 0,
              emphasis: {
                  focus: 'series'
              },
              data: seriesData.NEW.line,
            //   smooth: true // Optional: smooth the line
          }
      ]
  };

  // Initialize or update the chart with the new options
  const chart = echarts.init(document.getElementById('projection-chart'));
  chart.setOption(option);
}

function populateCpkRecords(data) {
  const months = ['1', '2', '3', '4', '5', '6',
      '7', '8', '9', '10', '11', '12'];
  const text_months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
        'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];


  const seriesData = {
      NEW: {line: [] },
  };

  let minProjection = Infinity, maxProjection = -Infinity;

  months.forEach(() => {
      seriesData.NEW.line.push(0);
  });

  data.forEach(item => {
      const monthIndex = item.month - 1; // Adjust for zero-based index
      seriesData['NEW'].line[monthIndex] = item.tyre_cost_km;

      const tyre_cost_km = parseFloat(item.tyre_cost_km);

      // Update min/max values for tyre_price
      if (tyre_cost_km < minProjection) minProjection = tyre_cost_km;
      if (tyre_cost_km > maxProjection) maxProjection = tyre_cost_km;

  });

  const option = {
      tooltip: {
          trigger: 'axis',
          axisPointer: {
              type: 'cross'
          },
          formatter: function (params) {
              let category = params[0].name;
              index = parseFloat(category) - 1;
              let tooltipText = `<strong>${text_months[index]}</strong><br>`;
              let formatter = new Intl.NumberFormat('en-US', {
                style: 'decimal',
                minimumFractionDigits: 3,
                maximumFractionDigits: 3
              });
              params.forEach(item => {
                if (item.seriesType === 'line') {
                  tooltipText += `${item.marker} RM${formatter.format(item.value)}KM<br>`;
                }
              });
              return tooltipText;
          }
      },
      legend: {
          data: ['NEW'] // Same legend for bars and lines
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
              name: 'CPK',
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
              max: maxProjection
          }
      ],
      series: [
          // Line series for 'NEW'
          {
              name: 'CPK',
              type: 'line',
              yAxisIndex: 0,
              emphasis: {
                  focus: 'series'
              },
              data: seriesData.NEW.line,
            //   smooth: true // Optional: smooth the line
          }
      ]
  };

  // Initialize or update the chart with the new options
  const chart = echarts.init(document.getElementById('cpk-chart'));
  chart.setOption(option);
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

          switch(item.axle_type){
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
              let formatter = new Intl.NumberFormat('en-US', {
                style: 'decimal',
                minimumFractionDigits: 3,
                maximumFractionDigits: 3
              });
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

