
/*!
 * The following copyright notice may not be removed under any circumstances.
 * 
 * Designer:
 * Radja Lomas
 */
var lastClass = '';
        /* custom */
        function adaptStyle() {
                // dimensions
                var $scrWidth = $(window).width();
                var $scrHeight = $(window).height();
                var $scrollTop = $(window).scrollTop();
                var $infoText = $scrWidth + 'px x ' + $scrHeight + 'px <br />Scroll : ' + $scrollTop + 'px <br> ' + navigator.userAgent ;

                // reset
                if ( $scrWidth > 1699 ) {
                        lastClass = 'xlarge';
                        $('.rsd_w').addClass('rsd-w-' + lastClass);
                } else if  ($scrWidth > 1289) {		
                        lastClass = 'large';
                        $('.rsd_w').addClass('rsd-w-' + lastClass);
                } else if  ($scrWidth > 1039) {		
                        lastClass = 'medium';
                        $('.rsd_w').addClass('rsd-w-' + lastClass);
                } else  {		
                        lastClass = 'narrow';
                        $('.rsd_w').addClass('rsd-w-' + lastClass);
                }
                
                if ( $scrHeight > 779 ) {
                        $('.rsd_w').addClass('rsd-h-high');
                } else {		
                        $('.rsd_w').addClass('rsd-h-small');
                }
                
                
                //alert($infoTxt);
                return $infoText;
        }

        function resetStyle() {

                // remove any previous
                $('.rsd_w').removeClass('rsd-w-xlarge');                
                $('.rsd_w').removeClass('rsd-w-large');                
                $('.rsd_w').removeClass('rsd-w-medium');
                $('.rsd_w').removeClass('rsd-w-narrow');
                                
                $('.rsd_w').removeClass('rsd-h-small');
                $('.rsd_w').removeClass('rsd-h-high');
        }
        




        /*** sniff the UA of the client and show hidden div's for that device ***/
        var customizeForDevice = function(){
                var ua = navigator.userAgent;
                var checker = {
                  iphone: ua.match(/(iPhone|iPod|iPad)/),
                  blackberry: ua.match(/BlackBerry/),
                  android: ua.match(/Android/)
                };
                if (checker.android){
                        $('.android-only').show();
                }
                else if (checker.iphone){
                        $('.idevice-only').show();
                }
                else if (checker.blackberry){
                        $('.berry-only').show();
                }
                else {
                        $('.unknown-device').show();
                }
        }