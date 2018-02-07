var TotalCart = {

    view : null,

    type: 't',

    new_price: 0,
    old_price: 0,
    renew_price: 0,

    discount_active: false,
    discount : 0,

    coupon_price : 0,
    coupon_code : null,

    total : 0,
    fee: fee,

init : function(){

    this.discount = discount;

    this.view = jQuery("#payment_total_cart");

    this.update();
},

update : function(){

    this.view.find('.subtotal span').text(Math.round(this.getSubTotal()));

    var discount = this.view.find('.discount');

    if(this.discount_active){
        discount.show();
        discount.find('span').text(this.discount);
    } else {
        discount.hide();
    }

    var coupon = this.view.find('.coupon ');

    if(this.coupon_code){
        coupon.show();
        coupon.find('span').text(this.coupon_price);
    } else {
        coupon.hide();
    }

    if(this.isRecurring()){
        this.view.find('.total').hide();
        this.view.find('.monthly').show().find('span').text(this.getTotalPrice());
        this.view.find('.fee').show();

        var first_payment = this.getFirstPayment();

        if(first_payment != null){
            this.view.find('.first_payment').show().find('span').text(first_payment);
        }

    } else {
        this.view.find('.first_payment, .monthly, .fee').hide();
        this.view.find('.total').show().find('span').text(this.getTotalPrice());
    }


},

isRecurring: function(){
    return this.type == 'm';
},

getFirstPayment : function(){

    if(this.type != 'm'){
        return null;
    }

    var first_payment = ( this.discount_active ? this.new_price * ( (100 - this.discount) / 100) : this.new_price ) - this.old_price;
    first_payment += ( this.discount_active ? this.renew_price * ( (100 - this.discount) / 100) : this.renew_price );

    if(this.coupon_code){
        first_payment -= this.coupon_price;
    }

    if(first_payment < 0){
        first_payment = 0;
    }

    first_payment += this.fee;

    return Math.round(first_payment);
},

getSubTotal : function(){

    var compensation_price = this.new_price - this.old_price;
    var price = ( this.type != 'm' && compensation_price > 0 ) ? compensation_price : 0;
    price += this.type == 'm'? this.new_price + this.renew_price : this.renew_price;

    return price;
},

getTotalPrice : function(){

    var total = 0;

    if(this.isRecurring()){

        if(this.discount_active){
            total += (this.renew_price + this.new_price) * ( (100 - this.discount) / 100);
        } else {
            total += this.renew_price + this.new_price;
        }

        total += this.fee;

    } else {
        if(this.discount_active){
            total += (this.renew_price + this.new_price) * ( (100 - this.discount) / 100);
        } else {
            total += this.renew_price + this.new_price;
        }

        if(this.old_price > 0 && this.new_price > 0){
            var compensation = this.new_price - this.old_price;

            if(compensation > 0){
                total -= this.old_price;
            } else {
                total -= this.old_price + compensation;
            }
        }

        if(this.coupon_code){
            total -= this.coupon_price;
        }
    }

    if(total < 0){
        total = 0;
    }

    return Math.round(total);
}

};

jQuery(document).ready(function(){
    TotalCart.init();
});