(function($)
{
  $.fn.sexytime = function(options)
  {
    options = options || {}
    console.log(['sexytime options', options])

    if(typeof options.intervalSeconds === 'undefined')
    {
      options.intervalSeconds = 60;
    }

    if(typeof options.type === 'undefined')
    {
      options.type = 'fromNow';
    }

    if(typeof options.relativeTime === 'object')
    {
      moment.relativeTime = options.relativeTime;
    }
    else
    {
      options.relativeTime = moment.relativeTime;
    }

    $(this).find('time').each(function()
    {
      dateString = $(this).attr('datetime');
      momentDateObject = moment(dateString);
      momentDateObject.subtract('minutes', (new Date).getTimezoneOffset())

      if(options.type === 'fromNow')
      {
        renderedDate = momentDateObject.fromNow();
      }

      else
      {
        renderedDate = momentDateObject.format(options.format);
      }

      $(this).html(renderedDate);
    });

    setTimeout(function() { $(this).sexytime(options) }, options.intervalSeconds*1000);
  }

})(jQuery);