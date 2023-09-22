app.planPay = {};

app.planPay.config={
    button:'.btn-pay-pricing',
    coupon:'#coupon',
    link:'#process-link'
};

app.planPay.process=function(e){
    var self=this;
    var route=$(e.target).parents('button').data('href');
    var coupon=$(self.config.coupon).val();
    if(coupon){
        route+='?coupon='+coupon;
    }
    $(self.config.link).attr('href',route);
    $(self.config.link)[0].click();
    return false;
};
app.planPay.init=function(){
    // $(document).delegate(this.config.button,'click',this.process.bind(this));
};
app.planPay.init();
