var canvas = '';
var font_15 = 15;
var font_20 = 20;
var font_25 = 25;

$(function(){
    canvas = new Canvas();
    canvas.bg_img_change();

    $("#button_help").click(function(){
        canvas.water(this);
        $("#game_start").css('height', '400px');
        $("#start_up").css('height', '15%');
        $("#start_botton").css('height', '15%');
        $("#help_text").fadeIn();
    })
    $("#button_start").click(function(){
        canvas.water(this);
        canvas.game_s = true;
        $("#game_start").fadeOut();
        $("#game_windows").fadeOut();
        $("#start").click();
    })
    $("#button_name").click(function(){
        canvas.water(this);
        $("#game_end").hide();
        $("#game_count").show();
        $("#table tr:not(:first)").remove();
        $.ajax({
            url:"php/register.php",
            type:"POST",
            data:{
                name: $("#name").val(),
                score: canvas.score,
                time: canvas.t,
            },
            success:function(data){
                data = JSON.parse(data);
                
                data.sort(function(a, b) {
                    if(parseInt(a.score) < parseInt(b.score)){
                        return 1;
                    }
                    if( (parseInt(a.score) == parseInt(b.score)) && parseInt(a.time) < parseInt(b.time)){
                        return 1;
                    }
                    return -1;
                });
                
                var htmlTags = [];
                $.each(data, function(key, val){
                    val.position = key && val.score === data[key - 1].score && val.time === data[key - 1].time ? data[key - 1].position : key + 1;
                    htmlTags.push(`
                        <tr><td>${ val.position }</td><td>${ val.name }</td><td>${ val.score }</td><td>${ val.time }</td></tr>
                    `);
                });
                $("#table tbody").html(htmlTags);
            }
        })
    })
    $("#button_restart").click(function(){
        canvas.water(this);
        canvas = new Canvas();
        canvas.game_s = true;
        canvas.bg_img_change();
        
        $("#canvas img:not(:first)").remove();
        $("#name").val('');
        $("#game_count").fadeOut();
        $("#game_windows").fadeOut();
        $("#audio1").get(0).load();
        $("#start").click();
    })

    $("#start").click(function(){
        canvas.start_stop = false;
        $("#start").addClass('hide');
        $("#stop").removeClass('hide');
        $("#audio1").get(0).play();
        canvas.game_start();
    })
    $("#stop").click(function(){
        canvas.start_stop = false;
        $("#start").removeClass('hide');
        $("#stop").addClass('hide');
        $("#audio1").get(0).pause();
        canvas.game_stop();
    })

    $("#sound_on").click(function(){
        $("#sound_on").addClass('hide');
        $("#sound_off").removeClass('hide');
        $("#audio1").get(0).volume = 0;
        canvas.sound = false;
    })
    $("#sound_off").click(function(){
        $("#sound_on").removeClass('hide');
        $("#sound_off").addClass('hide');
        $("#audio1").get(0).volume = 1;
        canvas.sound = true;
    })

    $("#font_up").click(function(){
        if(font_15+1 > 20){
            font_15 = 20;
        }else{
            font_15 += 1;
        }
        if(font_20+1 > 25){
            font_20 = 25;
        }else{
            font_20 += 1;
        }
        if(font_25+1 > 30){
            font_25 = 30;
        }else{
            font_25 += 1;
        }
        $(".font_15").css('font-size', font_15+"px");
        $(".font_20").css('font-size', font_20+"px");
        $(".font_25").css('font-size', font_25+"px");
    })
    $("#font_down").click(function(){
        if(font_15-1 < 10){
            font_15 = 10;
        }else{
            font_15 -= 1;
        }
        if(font_20-1 < 15){
            font_20 = 15;
        }else{
            font_20 -= 1;
        }
        if(font_25-1 < 20){
            font_25 = 20;
        }else{
            font_25 -= 1;
        }
        $(".font_15").css('font-size', font_15+"px");
        $(".font_20").css('font-size', font_20+"px");
        $(".font_25").css('font-size', font_25+"px");
    })


    $("body").keydown(function(e){
        if(canvas.game_s && canvas.start_stop && canvas.keydown && e.keyCode == 32){
            canvas.keydown = false;
            canvas.attack_add(1);
            canvas.audio_add(1);
        }
        if(canvas.game_s && canvas.keydown && e.keyCode == 80){
            canvas.keydown = false;
            if(canvas.start_stop){
                canvas.start_stop = false;
                $("#stop").click();
            }else{
                canvas.start_stop = true;
                $("#start").click();
            }
        }
    }).keyup(function(e){
        if(e.keyCode == 80 || e.keyCode == 32){
            canvas.keydown = true;
        }
        if($("#name").val() == ''){
            $("#button_name").attr('disabled', 'disabled');
            $("#button_name").addClass('button_not_click');
            $("#button_name").removeClass('button_change_color');
        }else{
            $("#button_name").removeAttr('disabled');
            $("#button_name").removeClass('button_not_click');
            $("#button_name").addClass('button_change_color');
        }
    })


    $("#rock_fire").click(function(){
        canvas.attack_add(1);
        canvas.audio_add(1);
    })
    $("#rock_move").on('mousemove', function(e){
        var ox = $(this).offset().left+60;
        var oy = $(this).offset().top+60;
        var x = (e.pageX-ox)/10;
        var y = (e.pageY-oy)/10;

        if(x > 0){
            x += canvas.label/2;
        }else{
            x -= canvas.label/2;
        }
        if(y > 0){
            $(canvas.shape0.obj).css('transform', 'rotate('+(y*2)+'deg)');
            y += canvas.label/2;
        }else{
            $(canvas.shape0.obj).css('transform', 'rotate('+(y*2)+'deg)');
            y -= canvas.label/2;
        }

        canvas.x = x;
        canvas.y = y;
    }).on('mouseleave', function(e){
        canvas.x = 0;
        canvas.y = 0;
    })
})

function Canvas(){//遊戲物件
    this.x = 0;
    this.y = 0;

    this.score = 0;
    this.label = 0;
    
    this.time = '';
    this.t = 0;
    this.all_time = 0;
    this.shape_time = 60;
    this.attack_time = 120;
    this.supply_time = 240;
    this.effect_time = 10;

    this.game_s = false;
    this.start_stop = false;
    this.keydown = true;
    this.sound = true;

    var shape = new Shape($(".shape0"), 'shape0', 15, 300, 0, 100, 50);
    this.shape0 = shape;
    this.shape1 = [];
    this.shape2 = [];
    this.shape3 = [];
    this.attack1 = [];
    this.attack2 = [];
    this.supply = [];

    this.bg = 0;
    this.bg_img = [];
}
function Shape(obj, name, power, top, left, width, height){//角色物件
    this.obj = obj || '';
    this.name = name || '';
    this.power = power || 0;
    this.delete = 0;

    this.top = top || 0;
    this.left = left || 0;

    this.width = width || 0;
    this.height = height || 0;

    this.number = 1; //特效
    this.tf = true; //特效
}

Shape.prototype.draw = function(){//更新角色
    switch(this.name){
        case"shape0":
            if( this.left+canvas.x > 0 && this.left+canvas.x+this.width < 960){
                this.left += canvas.x;
            }
            if( this.top+canvas.y > 0 && this.top+canvas.y+this.height < 600){
                this.top += canvas.y;
            }
        break;
        case"shape1":
            this.left = this.left - 2.8 - (canvas.label/2);    
        break;
        case"shape2":
            this.left = this.left - 2.6 - (canvas.label/2);
        break;
        case"shape3":
            this.left = this.left - 2.4 - (canvas.label/2);
        break;
        case"attack1":
            this.left = this.left + 3.5 + (canvas.label/2);
        break;
        case"attack2":
            this.left = this.left - 3.5 - (canvas.label/2);
        break;
        case"supply":
            this.top = this.top + 2.5 + (canvas.label/2);
        break;
    }
    $(this.obj).css('top', this.top);
    $(this.obj).css('left', this.left);
}
Shape.prototype.shape_add = function(type){//新增角色
    $("#canvas").append('<img src="image/shape'+type+'1.png" class="shape'+type+' img center">');
    this.obj = $('.shape'+type+':last');
    this.name = 'shape'+type;
    this.width = 80;
    this.height = 80;

    this.top = canvas.random(40, 480);
    this.left = 960;

    if(type == 1){
        this.power = 15;
        canvas.shape1.push(this);
    }else if(type == 2){
        this.power = 15;
        canvas.shape2.push(this);
    }else if(type == 3){
        this.power = 30;
        canvas.shape3.push(this);
    }

    $(this.obj).css('top', this.top);
    $(this.obj).css('left', this.left);
}
Shape.prototype.attack_add = function(obj, type){//新增攻擊
    $("#canvas").append('<img src="image/attack'+type+'.png" class="attack'+type+' img center">');
    this.obj = $('.attack'+type+':last');
    this.name = 'attack'+type;
    this.width = 40;
    this.height = 3;

    this.top = obj.top+(obj.height/2);
    if(type == 1){
        this.left = obj.left+obj.width;
        canvas.attack1.push(this);
    }else if(type == 2){
        this.left = obj.left-40;
        canvas.attack2.push(this);
    }

    $(this.obj).css('top', this.top);
    $(this.obj).css('left', this.left);
}
Shape.prototype.supply_add = function(){//新增油桶
    $("#canvas").append('<img src="image/supply.png" class="supply img center">');
    this.obj = $(".supply:last");
    this.name = 'supply';
    this.width = 50;
    this.height = 50;

    this.top = -50;
    this.left = canvas.random(40, 800);
    canvas.supply.push(this);

    $(this.obj).css('top', this.top);
    $(this.obj).css('left', this.left);
    $(this.obj).css('transform', 'rotate(-20deg)');
}
Shape.prototype.effect = function(){ //角色特效
    if(this.name == "shape0"){
        this.number += 1;
        if(this.number > 2){
            this.number = 1;
        }
        $(this.obj).attr('src', 'image/'+this.name+this.number+'.png');
    }else if(this.name == "shape1" || this.name == "shape2"){
        this.number += 1;
        if(this.number > 4){
            this.number = 1;
        }
        $(this.obj).attr('src', 'image/'+this.name+this.number+'.png');
    }else if(this.name == "shape3"){
        this.number += 2;
        if(this.number > 360){
            this.number = 0;
        }
        $(this.obj).css('transform', 'rotate('+this.number+'deg)');
    }else if(this.name == "supply"){
        if(this.tf){
            this.number += 2;
            $(this.obj).css('transform', 'rotate('+(this.number-40)+'deg)');
            if(this.number > 80){
                this.tf = false;
            }
        }else{
            this.number -= 2;
            $(this.obj).css('transform', 'rotate('+(this.number-40)+'deg)');
            if(this.number < 0){
                this.tf = true;
            }
        }
    }
}


Canvas.prototype.shape_add = function(){//新增角色
    this.shape_time += Math.abs(180-(this.label/2));
    if(this.shape_time <= 90){
        this.shape_time = 90;
    }
    for(i = 1 ; i <= this.random(2,2); i++){
        var shape = new Shape();
        shape.shape_add(this.random(1,3));
    }
}
Canvas.prototype.attack_add = function(type){//新增攻擊
    if(type == 1){
        var shape = new Shape();
        shape.attack_add(this.shape0, type);
    }else if(type == 2){
        this.attack_time +=  Math.abs(120-(this.label/2));
        if(this.attack_time <= 30){
            this.attack_time = 30;
        }
        for(i = 0 ; i < this.shape1.length ; i ++){
            var shape = new Shape();
            shape.attack_add(this.shape1[i], type);
        }
    }
}
Canvas.prototype.supply_add = function(){//新增油桶
    this.supply_time +=  Math.abs(300-(this.label/2));
    if(this.supply_time <= 120){
        this.supply_time = 120;
    }
    var shape = new Shape();
    shape.supply_add();
}
Canvas.prototype.audio_add = function(type){//新增音效
    if(!this.sound){
        return false;
    }
    if(type == 1){
        $("#game_audio").append('<audio src="sound/shoot.mp3" class="audio1" autoplay></audio>');
    }else{
        $("#game_audio").append('<audio src="sound/destroyed.mp3" class="audio2" autoplay></audio>');
    }
    window.setTimeout(function(){
        $("#game_audio .audio"+type+":first").remove();
    },1000)
}
Canvas.prototype.effect_change = function(){ //增加特效
    this.effect_time += 2;
    for(i = 0 ; i < this.shape1.length ; i++){ //敵人
        if(this.effect_time%10 == 0){
            this.shape1[i].effect();
        }
    }
    for(i = 0 ; i < this.shape2.length ; i++){ //友人
        if(this.effect_time%10 == 0){
            this.shape2[i].effect();
        }
    }
    for(i = 0 ; i < this.shape3.length ; i++){ //隕石
        this.shape3[i].effect();
    }
    for(i = 0 ; i < this.supply.length ; i++){ //補給包
        if(this.effect_time%3 == 0){
            this.supply[i].effect();
        }
    }
    if(this.effect_time%10 == 0){
        this.shape0.effect();
    }
}

Canvas.prototype.touch = function(){//碰撞判斷
    var shape0 = this.shape0;

    for(i = 0 ; i < this.shape1.length ; i++){
        if(
            Math.abs((shape0.left+(shape0.width/2)) - (this.shape1[i].left+(this.shape1[i].width/2))) < (shape0.width/2) + (this.shape1[i].width/2) &&
            Math.abs((shape0.top+(shape0.height/2)) - (this.shape1[i].top+(this.shape1[i].height/2))) < (shape0.height/2) + (this.shape1[i].height/2)
        ){
            this.audio_add(2);
            this.over();
            shape0.power -= 15;
            $(this.shape1[i].obj).remove();
            this.shape1.splice(i, 1);
            return false;
        } 
    }

    for(i = 0 ; i < this.shape2.length ; i++){
        if(
            Math.abs((shape0.left+(shape0.width/2)) - (this.shape2[i].left+(this.shape2[i].width/2))) < (shape0.width/2) + (this.shape2[i].width/2) &&
            Math.abs((shape0.top+(shape0.height/2)) - (this.shape2[i].top+(this.shape2[i].height/2))) < (shape0.height/2) + (this.shape2[i].height/2)
        ){
            this.audio_add(2);
            this.over();
            shape0.power -= 15;
            $(this.shape2[i].obj).remove();
            this.shape2.splice(i, 1);
            return false;
        } 
    }

    for(i = 0 ; i < this.shape3.length ; i++){
        if(
            Math.abs((shape0.left+(shape0.width/2)) - (this.shape3[i].left+(this.shape3[i].width/2))) < (shape0.width/2) + (this.shape3[i].width/2) &&
            Math.abs((shape0.top+(shape0.height/2)) - (this.shape3[i].top+(this.shape3[i].height/2))) < (shape0.height/2) + (this.shape3[i].height/2)
        ){
            this.audio_add(2);
            this.over();
            shape0.power -= 15;
            $(this.shape3[i].obj).remove();
            this.shape3.splice(i, 1);
            return false;
        } 
    }

    for(i = 0 ; i < this.attack2.length ; i++){
        if(
            Math.abs((shape0.left+(shape0.width/2)) - (this.attack2[i].left+(this.attack2[i].width/2))) < (shape0.width/2) + (this.attack2[i].width/2) &&
            Math.abs((shape0.top+(shape0.height/2)) - (this.attack2[i].top+(this.attack2[i].height/2))) < (shape0.height/2) + (this.attack2[i].height/2)
        ){
            this.audio_add(2);
            shape0.power -= 15;
            $(this.attack2[i].obj).remove();
            this.attack2.splice(i, 1);
            return false;
        } 
    }

    for(i = 0 ; i < this.supply.length ; i++){
        if(
            Math.abs((shape0.left+(shape0.width/2)) - (this.supply[i].left+(this.supply[i].width/2))) < (shape0.width/2) + (this.supply[i].width/2) &&
            Math.abs((shape0.top+(shape0.height/2)) - (this.supply[i].top+(this.supply[i].height/2))) < (shape0.height/2) + (this.supply[i].height/2)
        ){
            shape0.power += 15;
            $(this.supply[i].obj).remove();
            this.supply.splice(i, 1);
            return false;
        } 
    }

    for(j = 0 ; j < this.attack1.length ; j++){
        var attack1 = this.attack1[j];

        for(i = 0 ; i < this.shape1.length ; i++){
            if(
                Math.abs((attack1.left+(attack1.width/2)) - (this.shape1[i].left+(this.shape1[i].width/2))) < (attack1.width/2) + (this.shape1[i].width/2) &&
                Math.abs((attack1.top+(attack1.height/2)) - (this.shape1[i].top+(this.shape1[i].height/2))) < (attack1.height/2) + (this.shape1[i].height/2)
            ){
                $(this.attack1[j].obj).remove();
                this.attack1.splice(j, 1);

                this.shape1[i].power -= 15;
                this.score += 5;
                $("#canvas").append('<div class="touch" style="position: absolute; width: 80px; height: 80px; top: '+this.shape1[i].top+'px; left: '+this.shape1[i].left+'px;"></div>');

                return false;
            } 
        }

        for(i = 0 ; i < this.shape2.length ; i++){
            if(
                Math.abs((attack1.left+(attack1.width/2)) - (this.shape2[i].left+(this.shape2[i].width/2))) < (attack1.width/2) + (this.shape2[i].width/2) &&
                Math.abs((attack1.top+(attack1.height/2)) - (this.shape2[i].top+(this.shape2[i].height/2))) < (attack1.height/2) + (this.shape2[i].height/2)
            ){
                $(this.attack1[j].obj).remove();
                this.attack1.splice(j, 1);

                this.shape2[i].power -= 15;
                this.score -= 10;
                $("#canvas").append('<div class="touch" style="position: absolute; width: 80px; height: 80px; top: '+this.shape2[i].top+'px; left: '+this.shape2[i].left+'px;"></div>');

                return false;
            } 
        }

        for(i = 0 ; i < this.shape3.length ; i++){
            if(
                Math.abs((attack1.left+(attack1.width/2)) - (this.shape3[i].left+(this.shape3[i].width/2))) < (attack1.width/2) + (this.shape3[i].width/2) &&
                Math.abs((attack1.top+(attack1.height/2)) - (this.shape3[i].top+(this.shape3[i].height/2))) < (attack1.height/2) + (this.shape3[i].height/2)
            ){
                $(this.attack1[j].obj).remove();
                this.attack1.splice(j, 1);

                this.shape3[i].power -= 15;

                if(this.shape3[i].power == 0){
                    this.score += 10;
                    $("#canvas").append('<div class="touch" style="position: absolute; width: 80px; height: 80px; top: '+this.shape3[i].top+'px; left: '+this.shape3[i].left+'px;"></div>');
                }else{
                    $(this.shape3[i].obj).attr('src', 'image/shape32.png');
                }
                return false;
            } 
        }
    }
}

Canvas.prototype.ready_remove = function(){//預備刪除
    for(i = 0 ; i < this.shape1.length ; i ++){
        if( (this.shape1[i].power <= 0 || this.shape1[i].left <= -80) && this.shape1[i].delete<= 100 ){
            this.shape1[i].delete += 1;
        }
    }
    for(i = 0 ; i < this.shape2.length ; i ++){
        if( (this.shape2[i].power <= 0 || this.shape2[i].left <= -80) && this.shape2[i].delete<= 100 ){
            this.shape2[i].delete += 1;
        }
    }
    for(i = 0 ; i < this.shape3.length ; i ++){
        if( (this.shape3[i].power <= 0 || this.shape3[i].left <= -80) && this.shape3[i].delete<= 100 ){
            this.shape3[i].delete += 1;
        }
    }
    for(i = 0 ; i < this.attack1.length ; i ++){
        if(this.attack1[i].left >= 960 && this.attack1[i].delete<= 100){
            this.attack1[i].delete += 1;
        }
    }
    for(i = 0 ; i < this.attack2.length ; i ++){
        if(this.attack2[i].left <= -40 && this.attack2[i].delete<= 100){
            this.attack2[i].delete += 1;
        }
    }
    for(i = 0 ; i < this.supply.length ; i ++){
        if(this.supply[i].top >= 600 && this.supply[i].delete<= 100){
            this.supply[i].delete += 1;
        }
    }
}
Canvas.prototype.remove = function(){//刪除
    for(i = 0 ; i < this.shape1.length ; i ++){
        if(this.shape1[i].delete >= 5){
            $('.touch').remove();
            $(this.shape1[i].obj).remove();
            this.shape1.splice(i, 1);
            return false;
        }
    }
    for(i = 0 ; i < this.shape2.length ; i ++){
        if(this.shape2[i].delete >= 5){
            $('.touch').remove();
            $(this.shape2[i].obj).remove();
            this.shape2.splice(i, 1);
            return false;
        }
    }
    for(i = 0 ; i < this.shape3.length ; i ++){
        if(this.shape3[i].delete >= 5){
            $('.touch').remove();
            $(this.shape3[i].obj).remove();
            this.shape3.splice(i, 1);
            return false;
        }
    }
    for(i = 0 ; i < this.attack1.length ; i ++){
        if(this.attack1[i].delete >= 5){
            $(this.attack1[i].obj).remove();
            this.attack1.splice(i, 1);
            return false;
        }
    }
    for(i = 0 ; i < this.attack2.length ; i ++){
        if(this.attack2[i].delete >= 5){
            $(this.attack2[i].obj).remove();
            this.attack2.splice(i, 1);
            return false;
        }
    }
    for(i = 0 ; i < this.supply.length ; i ++){
        if(this.supply[i].delete >= 5){
            $(this.supply[i].obj).remove();
            this.supply.splice(i, 1);
            return false;
        }
    }
}
Canvas.prototype.draw = function(){//更新角色
    for(i = 0 ; i < this.shape1.length ; i ++){
        this.shape1[i].draw();
    }
    for(i = 0 ; i < this.shape2.length ; i ++){
        this.shape2[i].draw();
    }
    for(i = 0 ; i < this.shape3.length ; i ++){
        this.shape3[i].draw();
    }
    for(i = 0 ; i < this.attack1.length ; i ++){
        this.attack1[i].draw();
    }
    for(i = 0 ; i < this.attack2.length ; i ++){
        this.attack2[i].draw();
    }
    for(i = 0 ; i < this.supply.length ; i ++){
        this.supply[i].draw();
    }
    this.shape0.draw();
}


Canvas.prototype.game_start = function(){//遊戲開始
    canvas.start_stop = true;

    canvas.all_time += 1;

    if(canvas.all_time%60 == 0){
        canvas.t += 1;
        canvas.shape0.power -= 1;
    }if(canvas.all_time%300 == 0){
        canvas.label += 1;
    }if(canvas.all_time >= canvas.shape_time){
        canvas.shape_add();
    }if(canvas.all_time >= canvas.attack_time){
        canvas.attack_add(2);
    }if(canvas.all_time >= canvas.supply_time){
        canvas.supply_add();
    }if(canvas.all_time >= canvas.effect_time){
        canvas.effect_change();
    }

    canvas.bg -= 2;
    
    
    canvas.touch();
    canvas.ready_remove();
    canvas.remove();

    canvas.controller_change();
    canvas.draw();
    canvas.game_end();
    if(canvas.game_s){
        canvas.time = requestAnimationFrame(canvas.game_start);
    }
}
Canvas.prototype.game_stop = function(){//遊戲暫停
    this.start_stop = false;
    cancelAnimationFrame(this.time);
}
Canvas.prototype.game_end = function(){//遊戲結束
    if(this.shape0.power <= 0){
        $("#stop").click();
        this.game_s = false;
        this.over();
        $("#game_windows").fadeIn();
        $("#game_end").fadeIn();
    }
}

Canvas.prototype.controller_change = function(){//更新顯示
    if(this.shape0.power >= 30){
        this.shape0.power = 30;
    }
    if(this.shape0.power <= 0){
        this.shape0.power = 0;
    }
    $("#score").text(this.score);
    $("#time").text(this.t+"s");
    $("#power").text(this.shape0.power);
    $("#power_color").css('height', parseInt(this.shape0.power/30*100));
}
Canvas.prototype.water = function(obj){//特效水波
    $(obj).append('<div class="water" style="width: '+$(obj).width()+'px; height: '+$(obj).width()+'px;"></div>');
    window.setTimeout(function(){
        $(".width").remove();
    },200)
}
Canvas.prototype.over = function(){//特效晃動
    $("#game").addClass('over');
    window.setTimeout(function(){
        $("#game").removeClass('over');
    },200)
}
Canvas.prototype.bg_img_change = function(){//背景星球
    for(i = 1 ; i <= 6 ; i ++){
        var top = this.random(i*40, i*40+40);
        var left = this.random(i*70, i*70+70);
        $("#bg_img"+i).css('top', top);
        $("#bg_img"+i).css('left', left);
        this.bg_img[i] = left;
    }
}
Canvas.prototype.random = function(min, max){//產生亂數
    return parseInt(Math.random()*max)+min;
}
