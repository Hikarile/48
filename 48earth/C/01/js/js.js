var bg_position = 0;  // 星空背景移動
var bg_content_x = ['', 90, 700, 250, 750, 200, 500]; //星球背景x軸
var bg_content_y = ['', 70, 150, 240, 300, 400, 420]; //星球背景ㄗ軸

var game_time = "";
var canvas = ""; //Canvas的畫布變數

$(function(){
    canvas = new Canvas();
    canvas.draw();
    reset();

    $("#button_start").click(function(){ // 遊戲開始按鈕
        $("#game_windows").fadeOut();
        $(".restart[type=start]").click();
    })
    $("#button_how").click(function(){ // 遊戲教學按鈕
        $('#game_start').css('height', "400px");
        $('#game_start').css('margin-top', "-200px");
        $("#game_helop").show();
    })
    $("#button_restart").click(function(){ //重新開始
        game_time = "";
        canvas = new Canvas;
        canvas.draw();
        
        $("#audio0").get(0).load();
        $("#name").val('');
        $("#game_count").fadeOut();
        reset();
        $("#game_windows").fadeOut();
        $(".restart[type=start]").click();
    })

    
    $(".restart").click(function(){ // 開始,暫停
        if($(this).attr('type') == "start"){
            $("#game_start").fadeOut();
            $(this).hide();
            $(".restart[type=stop]").show();
            $("#audio0").get(0).play();
            game_start();
        }else{
            $(this).hide();
            $(".restart[type=start]").show();
            $("#audio0").get(0).pause();
            game_stop();
        }
    })
    $(".sound").click(function(){ // 音量on/off
        if($(this).attr('type') == "off"){
            $(this).hide();
            $(".sound[type=on]").show();
            $("#audio0").get(0).volume = 1;
            $("#audio1").get(0).volume = 1;
            $("#audio2").get(0).volume = 1;
        }else{
            $(this).hide();
            $(".sound[type=off]").show();
            $("#audio0").get(0).volume = 0;
            $("#audio1").get(0).volume = 0;
            $("#audio2").get(0).volume = 0;
        }
    })


    $("#font_size_up").click(function(){ // 字體放大
        var size = $("body, html").css('font-size').split("px")[0];
        var ol = $("ol").css('font-size').split("px")[0];
        if(size < 26){
            size = parseInt(size)+2;
            ol = parseInt(ol)+2;
            $("body, html").css('font-size', size+"px");
            $("span").css('font-size', size+"px");
            $("ol").css('font-size', ol+"px");
            $("button").css('font-size', size+"px");
        }
    })
    $("#font_size_down").click(function(){ // 字體縮小
        var size = $("body, html").css('font-size').split("px")[0];
        var ol = $("ol").css('font-size').split("px")[0];
        if(size > 14){
            size = parseInt(size)-2;
            ol = parseInt(ol)-2;
            $("body, html").css('font-size', size+"px");
            $("span").css('font-size', size+"px");
            $("ol").css('font-size', ol+"px");
            $("button").css('font-size', size+"px");
        }
    })

    $("#data_count2").click(function(){
        $("#data_count1").show();
        $("#data_count2").hide();
        $("#table1").hide();
        $("#table_div").show();
    })
    $("#data_count1").click(function(){
        $("#data_count1").hide();
        $("#data_count2").show();
        $("#table1").show();
        $("#table_div").hide();
    })

    $("#name").keyup(function(){  //輸入名字
        if($("#name").val() != ""){
            $("#continue").removeClass('click_off');
            $("#continue").addClass('button');
            $("#continue").removeAttr('disabled');
        }else{
            $("#continue").removeClass('button');
            $("#continue").addClass('click_off');
            $("#continue").attr('disabled', '');
        }
    })
    $("#continue").click(function(){ //送出資料到php檔案
        $.ajax({
            url: "php/register.php",
            type: "POST",
            data:{
                name: $("#name").val(),
                score: canvas.score,
                time: canvas.monster_time
            },
            success: function(data) {
                $('#table1 tr:not(:first), #table2 tr:not(:first)').remove();
                var data = JSON.parse(data);
                var max = 0;
                var count = 0;
                var tf = true;
                
                $.each(data, function(key, val){
                    if(parseInt(val[0]) >= parseInt(max)){
                        max = val[0];
                    }
                    var table = "<tr type='"+val[0]+"'><td>"+parseInt(key+1)+"</td><td>"+val[1]+"</td><td>"+val[2]+"</td><td>"+val[3]+"</td></tr>";
                    $("#table2").append(table);
                });
                $("#table2 tr[type="+max+"]").addClass("tr_color");
               
                for(i=0 ; i<=2 ; i++){
                    if(data[i] === undefined) break;
                    var table = "<tr><td>"+parseInt(i+1)+"</td><td>"+data[i][1]+"</td><td>"+data[i][2]+"</td><td>"+data[i][3]+"</td></tr>";
                    if(data[i][0] == max){
                        tf = false;
                        table = "<tr class='tr_color'><td>"+parseInt(i+1)+"</td><td>"+data[i][1]+"</td><td>"+data[i][2]+"</td><td>"+data[i][3]+"</td></tr>";
                    }
                    $("#table1").append(table);
                    count+=1;
                }
                if(count == 3 && tf){
                    $.each(data, function(key, val){
                        if(val[0] == max){
                            var table = "<tr class='tr_color'><td>"+parseInt(key+1)+"</td><td>"+val[1]+"</td><td>"+val[2]+"</td><td>"+val[3]+"</td></tr>";
                            $("#table1").append(table);
                        }
                    });
                }

                $("#game_end").fadeOut();
                $("#game_count").fadeIn();
            }
        })
    })

    $("body, html").keydown(function(e){ // 按下鍵盤
        if(e.keyCode == 80 && canvas.attack_tf){
            canvas.attack_tf = false;
            if(canvas.restart){
                $(".restart[type=stop]").click();
            }else{
                $(".restart[type=start]").click();
            }
        }else if(canvas.restart){
             canvas.keydown(e.keyCode);
        }
    })
    $("body, html").keyup(function(e){ // 放開鍵盤
        if(e.keyCode == 32 || e.keyCode == 80){
            canvas.attack_tf = true;
        }
    })

    $("#rocker").on('mousemove', function (e) {
        var w = $(this).width(),
            h = $(this).height(),
            cx = $(this).offset().left,
            cy = $(this).offset().top,
            ox = cx + w / 2,
            oy = cy + h / 2,
            x = (e.pageX - ox)/10,
            y = (e.pageY - oy)/10;

            if(x>0){
                x += (canvas.label/2)
            }else{
                x -= (canvas.label/2)
            }if(y>0){
                y += (canvas.label/2)
            }else{
                y -= (canvas.label/2)
            }

        canvas.x = x;
        canvas.y = y;
    }).on('mouseleave', function(e){
        canvas.x = 0;
        canvas.y = 0;
    });
    $("#fire").click(function(){
        canvas.shpe0_attack_produce();
    })

    $('button').on('click', function (e) { //按鈕的水波特效
        $('.ripple').remove();

        var x = $(this).offset().left,
            y = $(this).offset().top,
            w = $(this).width(),
            h = $(this).width();

        $(this).prepend('<span class="ripple rippleDrop"></span>');
        $('.ripple').css({
            top: e.pageY - y - h/2,
            left: e.pageX - x - w/2,
            width: w,
            height: h,
        })
    })
})


function Canvas(){ //畫布
    this.canvas = $("#canvas")[0];
    this.ctx = this.canvas.getContext('2d');
	this.ctx.lineCap = 'round';
    this.width = 960;
    this.heigth = 600;
    this.x = 0;
    this.y = 0;
    this.after_monster = "";

    this.label = 0;
    this.score = 0;
    this.monster_time = 0;
    this.random_time1 = 0;
    this.random_time2 = 3;
    
    this.time = "";
    this.m = 0;
    this.s = 0;

    this.attack_tf = true;
    this.restart = false;

    shape = new Shape("shape0", 15, 20, 260, $("#shape0").width(), $("#shape0").height());

    this.shape0 = shape; //主角飛船
    this.shape1 = []; //敵人飛船
    this.shape2 = []; //友方飛船
    this.shape3 = []; //隕石
    this.attack1 = []; //主角攻擊
    this.attack2 = []; //敵人攻擊
    this.supply = []; //補給包
}
function Shape(name, power, x1, y1, x2, y2){ //角色
    this.name = name || "";
    this.power = power || 0;
    
    this.number = 1;
    this.count = 0;

    this.sx = x1 || 0;
    this.sy = y1 || 0;
    this.ex = x2 || 0;
    this.ey = y2 || 0;
}


Shape.prototype.draw = function(ctx){ //畫Shape
    var name = "";
    switch (this.name){
        case "shape0":
            if(this.sx+canvas.x > 0 && this.sx+this.ex+canvas.x < 960){
                this.sx += canvas.x;
            }if( this.sy+canvas.y > 0 && this.sy+this.ex+canvas.y < 600){
                this.sy += canvas.y;
            }
            name = $("#"+this.name)[0];
            break;
        case "shape1":
            this.sx = this.sx-3-canvas.label;
            this.count += 1;
            if(this.count == 25){
                this.count = 0;
                this.number += 1;
                if(this.number == 5){
                    this.number = 1;
                }
            }
            name = $("#"+this.name+this.number)[0];
            break;
        case "shape2":
            this.sx = this.sx-1-canvas.label;
            this.count += 1;
            if(this.count == 25){
                this.count = 0;
                this.number += 1;
                if(this.number == 5){
                    this.number = 1;
                }
            }
            name = $("#"+this.name+this.number)[0];
            break;
        case "shape3": 
            this.sx = this.sx-2-canvas.label;
            this.number+=2;
            if(this.number >= 360){
                this.number = 1;
            }
            $("#"+this.name).css('transform', 'rotate('+this.number+'deg)');
            name = $("#"+this.name)[0];
            break;
        case "shape4":
            this.sx = this.sx-1.5-canvas.label;
            this.number+=2;
            if(this.number >= 360){
                this.number = 1;
            }
            $("#"+this.name).css('transform', 'rotate('+this.number+'deg)');
            name = $("#"+this.name)[0];
            break;
        case "attack1":
            this.sx = this.sx+4+canvas.label;
            name = $("#"+this.name)[0];
            break;
        case "attack2":
            this.sx = this.sx-4-canvas.label;
            name = $("#"+this.name)[0];
            break;
        case "supply":
            this.sy = this.sy+1+canvas.label;
            name = $("#"+this.name)[0];
            break;
    }
    ctx.drawImage(name, this.sx, this.sy, this.ex, this.ey);
}
Shape.prototype.shape_add = function(name){ //新增物件
    this.name = name;
    
    this.sx = 970;
    this.sy = random(10,510);
    this.ex = $("#"+name).width();
    this.ey = $("#"+name).height();

    this.number = 1;
    this.count = 1;

    if(name == "shape1"){
        this.power = 15;
        canvas.shape1.push(this);
    }else if(name == "shape2"){
        this.power = 15;
        canvas.shape2.push(this);
    }else if(name == "shape3"){
        this.power = 30;
        canvas.shape3.push(this);
    }else if(name == "shape4"){
        this.power = 30;
        canvas.shape3.push(this);
    }
    power_change();
}
Shape.prototype.attack_add = function(shap, number){ //新增攻擊
    var x = shap.sx + shap.ex;
    var y = shap.sy + (shap.ey/2);

    if(number == 0){
        this.name = "attack1";
        this.power = 0;
        this.sx = x;
        this.sy = y;
        this.ex = $("#attack1").width();
        this.ey = $("#attack1").height();
        canvas.attack1.push(this);
    }else{
        this.name = "attack2";
        this.power = 0;
        this.sx = shap.sx - $("#attack2").width();
        this.sy = y;
        this.ex = $("#attack2").width();
        this.ey = $("#attack2").height();
        canvas.attack2.push(this);
    }
}
Shape.prototype.supply_add = function(name){ //新增補給包
    this.name = name;
    
    this.sx = random(50,870);
    this.sy = -30;
    this.ex = $("#"+name).width();
    this.ey = $("#"+name).height();
    canvas.supply.push(this);
}


Canvas.prototype.keydown = function(keycode){ //按鍵觸發事件
    var shap = this.shape0;
    if(keycode == 38 && shap.sy-10 >= 0){ //上
        shap.sy -= 10;
    }if(keycode == 40 && shap.sy+shap.ey+10 <= 600){ //下
        shap.sy += 10;
    }if(keycode == 37 && shap.sx-10 >= 0){ //左
        shap.sx -= 10;
    }if(keycode == 39 && shap.sx+shap.ex+10 <= 960){ //右
        shap.sx += 10;
    }if(keycode == 32 && this.attack_tf){ //攻擊
        this.shpe0_attack_produce();
    }
}
Canvas.prototype.touch = function(){ //碰撞
    var shape0 = this.shape0;

    //主角撞敵人
    for(i = this.shape1.length-1 ; i >= 0 ; i--){
        for(j = shape0.sy ; j <= shape0.sy+shape0.ey ; j++){
            for(k = shape0.sx ; k <= shape0.sx+shape0.ex ; k++){
                var shape1 = this.shape1[i];
                if(
                    j >= shape1.sy &&
                    j <= shape1.sy + shape1.ey &&
                    k >= shape1.sx &&
                    k <= shape1.sx + shape1.ex
                ){
                    this.touch_change("touch1", shape0, i);
                    return false
                }
            }
        }
    }

    //主角撞友人
    for(i = this.shape2.length-1 ; i >= 0 ; i--){
        for(j = shape0.sy ; j <= shape0.sy+shape0.ey ; j++){
            for(k = shape0.sx ; k <= shape0.sx+shape0.ex ; k++){
                var shape2 = this.shape2[i];
                if(
                    j >= shape2.sy &&
                    j <= shape2.sy + shape2.ey &&
                    k >= shape2.sx &&
                    k <= shape2.sx + shape2.ex
                ){
                    this.touch_change("touch2", shape0, i);
                    return false
                }
            }
        }
    }

    //主角撞隕石
    for(i = this.shape3.length-1 ; i >= 0 ; i--){
        for(j = shape0.sy ; j <= shape0.sy+shape0.ey ; j++){
            for(k = shape0.sx ; k <= shape0.sx+shape0.ex ; k++){
                var shape3 = this.shape3[i];
                if(
                    j >= shape3.sy &&
                    j <= shape3.sy + shape3.ey &&
                    k >= shape3.sx &&
                    k <= shape3.sx + shape3.ex
                ){
                    this.touch_change("touch3", shape0, i);
                    return false
                }
            }
        }
    }

    //主角被攻擊
    for(i = this.attack2.length-1 ; i >= 0 ; i--){
        for(j = shape0.sy ; j <= shape0.sy+shape0.ey ; j++){
            for(k = shape0.sx ; k <= shape0.sx+shape0.ex ; k++){
                var attack2 = this.attack2[i];
                if(
                    j >= attack2.sy &&
                    j <= attack2.sy + attack2.ey &&
                    k >= attack2.sx &&
                    k <= attack2.sx + attack2.ex
                ){
                    this.touch_change("touch4", shape0, i);
                    return false
                }
            }
        }
    }
    
    for(k = this.attack1.length-1; k >= 0 ; k--){
        var attack1 = this.attack1[k];

        //攻擊打中飛船
        for(i = this.shape1.length-1 ; i >= 0 ; i--){
            for(j = attack1.sy ; j <= attack1.sy+attack1.ey ; j++){
                var shape1 = this.shape1[i];
                if(
                    j >= shape1.sy &&
                    j <= shape1.sy + shape1.ey &&
                    attack1.sx + attack1.ex >= shape1.sx &&
                    attack1.sx + attack1.ex <= shape1.sx + shape1.ex
                ){
                    tf = false;                    
                    this.touch_change("touch5", k, shape1);
                    return false
                }
            }
        }

        //攻擊打中友方
        for(i = this.shape2.length-1 ; i >= 0 ; i--){
            for(j = attack1.sy ; j <= attack1.sy+attack1.ey ; j++){
                var shape2 = this.shape2[i];
                if(
                    j >= shape2.sy &&
                    j <= shape2.sy + shape2.ey &&
                    attack1.sx + attack1.ex >= shape2.sx &&
                    attack1.sx + attack1.ex <= shape2.sx + shape2.ex
                ){
                    tf = false;  
                    this.touch_change("touch6", k, shape2);
                    return false
                }
            }
        }

        //攻擊打中隕石
        for(i = this.shape3.length-1 ; i >= 0 ; i--){
            for(j = attack1.sy ; j <= attack1.sy+attack1.ey ; j++){
                var shape3 = this.shape3[i];
                if(
                    j >= shape3.sy &&
                    j <= shape3.sy + shape3.ey &&
                    attack1.sx + attack1.ex >= shape3.sx &&
                    attack1.sx + attack1.ex <= shape3.sx + shape3.ex
                ){
                    tf = false;  
                    this.touch_change("touch7", k, shape3);
                    return false
                }
            }
        }
    }

    //主角吃補給包
    for(i = this.supply.length-1 ; i >= 0 ; i--){
        for(j = shape0.sy ; j <= shape0.sy+shape0.ey ; j++){
            for(k = shape0.sx ; k <= shape0.sx+shape0.ex ; k++){
                var supply = this.supply[i];
                if(
                    j >= supply.sy &&
                    j <= supply.sy + supply.ey &&
                    k >= supply.sx &&
                    k <= supply.sx + supply.ex
                ){
                    this.touch_change("touch8", shape0, i);
                    return false
                }
            }
        }
    }
}
Canvas.prototype.touch_change = function(type, a, b){ //碰撞後的處理
    switch(type){
        case "touch1": //主角撞敵人
            a.power -= 15;
            this.shape1.splice(b, 1);
            $("#audio2").get(0).play();
            break;
        case "touch2": //主角撞友人
            a.power -= 15;
            this.shape2.splice(b, 1);
            $("#audio2").get(0).play();
            break;
        case "touch3": //主角撞隕石
            a.power -= 15;
            this.shape3.splice(b, 1);
            $("#audio2").get(0).play();
            break;
        case "touch4": //主角被攻擊
            a.power -= 15;
            this.attack2.splice(b, 1);
            break;
        case "touch5": //攻擊打中飛船
            this.attack1.splice(a, 1);
            b.power -= 15;
            if(b.power == 0){
                this.score += 5;
                $("#audio2").get(0).play();
            }
            break;
        case "touch6": //攻擊打中友方
            this.attack1.splice(a, 1);
            b.power -= 15;
            if(b.power == 0){
                this.score -= 10;
                $("#audio2").get(0).play();
            }
            break;
        case "touch7": //攻擊打中隕石
            this.attack1.splice(a, 1);
            b.power -= 15;
            if(b.power == 0){
                this.score += 10;
                $("#audio2").get(0).play();
            }
            break;
        case "touch8": //主角吃補給包
            a.power += 15;
            if(a.power > 30){
                a.power = 30;
            }
            this.supply.splice(b, 1);
            break;
    }
    power_change();
    $("#score").text(this.score);
}


Canvas.prototype.shpe0_attack_produce = function(){ //產生我方攻擊
    $("#audio1").get(0).play();
    shape = new Shape();
    shape.attack_add(this.shape0, 0);
    this.attack_tf = false;
}
Canvas.prototype.monster_produce = function(){ //產生敵軍
    if(this.label == 0){
        this.random_time1 += random(3,4);//3-6
    }else{
        this.random_time1 += random(2,2);//2-4
    }
    var count = random(2,3); //2-4
    for(i=1 ; i<=count ; i++){
        var shape = new Shape(), name="";
        
        for(j=1;j<=100;j++){
            name = "shape"+random(1,4);//1-4
            if(this.after_monster != name){
                this.after_monster = name;
                break;
            }
        }

        shape.shape_add(name);
    }
}
Canvas.prototype.attack_produce = function(){ //產生攻擊
    for(i=0 ; i<canvas.shape1.length ; i++){
        shape = new Shape();
        shape.attack_add(canvas.shape1[i], 1);
    }
}
Canvas.prototype.supply_produce = function(){ //產生補給包
    this.random_time2 += random(5,3);//5-7
    shape = new Shape();
    shape.supply_add("supply");
}


Canvas.prototype.remove = function(){ //刪除物件
    for(i = 0 ; i < this.shape1.length ; i++){ //敵人
        if(this.shape1[i].sx + this.shape1[i].ex <= 0 || this.shape1[i].power <= 0){
            this.shape1.splice(i,1);
        }
    }for(i = 0 ; i < this.shape2.length ; i++){ //友人
        if(this.shape2[i].sx + this.shape2[i].ex <= 0 || this.shape2[i].power <= 0){
            this.shape2.splice(i,1);
        }
    }for(i = 0 ; i < this.shape3.length ; i++){ //隕石
        if(this.shape3[i].sx + this.shape3[i].ex <= 0 || this.shape3[i].power <= 0){
            this.shape3.splice(i,1);
        }
    }
    for(i = 0 ; i < this.attack1.length ; i++){ //主角攻擊
        if(this.attack1[i].sx >= 960){
            this.attack1.splice(i,1);
        }
    }for(i = 0 ; i < this.attack2.length ; i++){ //敵人攻擊
        if(this.attack2[i].sx + this.attack2[i].ex <= 0){
            this.attack2.splice(i,1);
        }
    }for(i = 0 ; i < this.supply.length ; i++){ //補給包
        if(this.supply[i].sy >= 600){
            this.supply.splice(i,1);
        }
    }
}
Canvas.prototype.draw = function(){ //畫Canvas
    this.ctx.clearRect(0, 0, 960, 600); //清除畫布

    for(i = 0 ; i < this.shape1.length ; i++){ //隕石
        this.shape1[i].draw(this.ctx);
    }
    for(i = 0 ; i < this.shape2.length ; i++){ //敵人
        this.shape2[i].draw(this.ctx);
    }
    for(i = 0 ; i < this.shape3.length ; i++){ //友人
        this.shape3[i].draw(this.ctx);
    }
    for(i = 0 ; i < this.attack1.length ; i++){ //主角攻擊
        this.attack1[i].draw(this.ctx);
    }
    for(i = 0 ; i < this.attack2.length ; i++){ //敵人攻擊
        this.attack2[i].draw(this.ctx);
    }
    for(i = 0 ; i < this.supply.length ; i++){ //補給包
        this.supply[i].draw(this.ctx);
    }
    this.shape0.draw(this.ctx); //主角
}
Canvas.prototype.game_end = function(){ //遊戲結束
    if(this.shape0.power <= 0){
        $(".restart[type=stop]").click();
        this.shape0.power = 0;

        $("#score").text(this.score);
        $("#power").text(this.shape0.power);
        $("#game_windows").show();
        $("#game_end").show();
    }
}
Canvas.prototype.time_change = function(){ //計時時間
    if(this.monster_time == this.random_time1) this.monster_produce();
    if(this.monster_time == this.random_time2) this.supply_produce();
    if(this.label < 10 && this.monster_time%5 == 0) this.label += 1;
    
    if(this.label <= 6){
        if(this.monster_time%2 == 0){
            this.attack_produce();
        }
    }else{
        this.attack_produce();
    }
    
    this.shape0.power -= 1;
    $("#power").text(this.shape0.power);
    $("#power-counter").css('height', parseInt(this.shape0.power/30*100)+"%");
    
    this.monster_time +=1;
    this.s += 1;
    if(this.s == 60){
        this.s = 0;
        this.m += 1;
    }
    ss = this.s; mm = this.m;
    if(this.s < 10){
        ss = "0" + this.s;
    }if(this.m < 10){
        mm = "0" + this.m;
    }
    
    $("#timing").text(mm + ":" + ss);
    this.time = setTimeout("canvas.time_change()",1000);
} 


function game_start(){  //遊戲開始
    canvas.time_change();
    canvas.restart = true;
    game_time = setInterval(function(){
        bg_change();
        canvas.touch();
        canvas.remove();
        canvas.draw();
        canvas.game_end();
    },20);
}
function game_stop(){  //遊戲暫停
    canvas.restart = false;
    clearInterval(game_time);
    clearTimeout(canvas.time);
}

function power_change(){
    $("#power").text(canvas.shape0.power);
    $("#power-counter").css('height', parseInt(canvas.shape0.power/30*100)+"%");
}
function reset(){ //重置
    $("#score").text("0");
    $("#power").text("15");
    $("#timing").text("00:00");
}
function bg_change(){ //背景移動
    for(i = 1 ; i <= 6 ; i++){
        if(bg_content_x[i]+$("#scenes"+i).width() <= 0){
            bg_content_x[i] = 960;
        }
        if(i == 1){
            bg_content_x[i] -= 1;
        }else if(i == 2){
            bg_content_x[i] -= 4;
        }else if(i == 3){
            bg_content_x[i] -= 2;
        }else if(i == 4){
            bg_content_x[i] -= 3;
        }else if(i == 5){
            bg_content_x[i] -= 5;
        }else if(i == 6){
            bg_content_x[i] -= 6;
        }
        $("#scenes"+i).css('transform', 'translate(' + bg_content_x[i] + 'px, ' + bg_content_y[i]  + 'px)');
    }
    bg_position -= 2;
    $("#game").css('background-position-x', bg_position+"px");
}
function random(min, max){ //產生亂數
    return parseInt(Math.random()*max)+min;
}
