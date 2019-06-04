/*! Tdrag 0.0.1 */
/**
 * Created by Tezml on 2016/5/26
 * You can modify my source code, if you have a good idea or a problem can be encountered by e-mail: tezml@tezml.com to find me.
 * 如果你想在项目中使用该插件，请不要删除该注释。
 */
;(function($,window,document,undefined){
    jQuery(function() {
        //插件制作


        $.fn.Tdrag = function (opt) {
            var call = {
                scope: null,//父级
                grid: null,//网格
                axis:"all",//上下或者左右
                pos:false,//是否记住位置
                handle:null,//手柄
                moveClass:"tezml",//移动时不换位加的class
                dragChange:false,//是否开启拖拽换位
                changeMode:"point",//point & sort
                cbStart:function(){},//移动前的回调函数
                cbMove:function(){},//移动中的回调函数
                cbEnd:function(){},//移动结束时候的回调函数
                random:false,//是否自动随机排序
                randomInput:null,//点击随机排序的按钮
                animation_options:{//运动时的参数
                    duration:800,//每次运动的时间
                    easing:"ease-out"//移动时的特效，ease-out、ease-in、linear
                },
                disable:false,//禁止拖拽
                disableInput:null//禁止拖拽的按钮
            };
            var dragfn = new Dragfn(this, opt);
            if (opt && $.isEmptyObject(opt) == false) {
                dragfn.options = $.extend(call, opt);
            } else {
                dragfn.options = call;
            }
            dragfn.firstRandom=true;
            var ele = dragfn.$element;
            dragfn.pack(ele,false);
            if(dragfn.options.randomInput!=null){
                $(dragfn.options.randomInput).bind("click",function(){
                    dragfn.pack(ele,true);
                })
            }
            //加载拓展jquery的函数
            dragfn.loadJqueryfn()
        };

        //依赖构造函数
        var Dragfn = function (ele, opt) {
            this.$element = ele;
            this.options = opt;
        };
        //构造函数方法
        Dragfn.prototype = {
            init: function (obj) {
                var self = this;
                self.ele=self.$element;
                self.handle=$(obj);//手柄
                self.options = self.options;
                self.disable = self.options.disable;
                self._start = false;
                self._move = false;
                self._end = false;
                self.disX = 0;
                self.disY = 0;
                self.zIndex=1000;
                self.moving=false;
                self.moves="";


                //父级
                self.box = $.type(self.options.scope)==="string" ? self.options.scope : null;
                //手柄
                if(self.options.handle!=null){
                    self.handle=$(obj).find(self.options.handle);
                }

                //三个事件
                self.handle.on("mousedown", function (ev) {
                    self.start(ev, obj);
                    obj.setCapture && obj.setCapture();
                    return false;
                });
                if(self.options.dragChange) {
                    $(obj).on("mousemove", function (ev) {
                        self.move(ev, obj);
                    });
                    $(obj).on("mouseup", function (ev) {
                        self.end(ev, obj);
                    });
                }else{
                    $(document).on("mousemove", function (ev) {
                        self.move(ev, obj);
                    });
                    $(document).on("mouseup", function (ev) {
                        self.end(ev, obj);
                    });
                }
            },
            //jquery调取函数时候用
            loadJqueryfn: function(){
                var self=this;
                $.extend({
                    //返回按照index排序的回调函数
                    sortBox:function(obj){
                        var arr=[];
                        for (var s = 0; s < $(obj).length; s++) {
                            arr.push($(obj).eq(s));
                        }
                        for ( var i = 0; i < arr.length; i++) {
                            for ( var j = i + 1; j < arr.length; j++) {
                                if(Number(arr[i].attr("index")) > Number(arr[j].attr("index"))){
                                    var temp = arr[i];
                                    arr[i] = arr[j];
                                    arr[j] = temp;
                                }
                            }
                        }
                        return arr
                    },
                    //随机排序函数
                    randomfn:function(obj){
                        self.pack($(obj),true);
                    },
                    //开启拖拽
                    disable_open:function(){
                        self.disable=false;
                    },
                    //禁止拖拽
                    disable_cloose:function(){
                        self.disable=true;
                    }
                });
            },
            toDisable: function(){
                var self=this;
                if(self.options.disableInput!=null){
                    $(self.options.disableInput).bind("click",function(){
                        if(self.disable==true){
                            self.disable=false
                        }else{
                            self.disable=true
                        }
                    })
                }
            },
            start: function (ev, obj) {
                var self = this;
                self.moved=obj;
                if (self.disable == true) {
                    return false
                }
                self._start = true;
                var oEvent = ev || event;
                self.disX = oEvent.clientX - obj.offsetLeft;
                self.disY = oEvent.clientY - obj.offsetTop;
                $(obj).css("zIndex",self.zIndex++);
                self.options.cbStart();
            },
            move: function (ev, obj) {
                var self = this;
                if (self._start != true) {
                    return false
                }
                if(obj!=self.moved){
                    return false
                }
                self._move = true;
                var oEvent = ev || event;
                var l = oEvent.clientX - self.disX;
                var t = oEvent.clientY - self.disY;
                //有父级限制
                if (self.box != null) {
                    var rule = self.collTestBox(obj,self.box);
                    if (l > rule.lmax) {
                        l = rule.lmax;
                    } else if (l < rule.lmin) {
                        l = rule.lmin;
                    }
                    if (t > rule.tmax) {
                        t = rule.tmax;
                    } else if (t < rule.tmin) {
                        t = rule.tmin;
                    }
                }
                if(self.options.axis=="all"){
                    obj.style.left = self.grid(obj, l, t).left  + 'px';
                    obj.style.top = self.grid(obj, l, t).top  + 'px';
                }else if(self.options.axis=="y"){
                    obj.style.top = self.grid(obj, l, t).top  + 'px';
                }else if(self.options.axis=="x"){
                    obj.style.left = self.grid(obj, l, t).left + 'px';
                }
               /* if(self.options.changeWhen=="move") {
                    if (self.options.changeMode == "sort") {
                        self.sortDrag(obj);
                    } else if (self.options.changeMode == "point") {
                        self.pointmoveDrag(obj);
                    }
                }else{
                    self.moveAddClass(obj);
                }*/
                if(self.options.pos==true){
                    self.moveAddClass(obj);
                }
                self.options.cbMove(obj,self);

            },
            end: function (ev, obj) {
                var self = this;
                if (self._start != true) {
                    return false
                }
                if(self.options.changeMode=="sort"&&self.options.pos==true){
                    self.sortDrag(obj);
                }else if(self.options.changeMode=="point"&&self.options.pos==true){
                    self.pointDrag(obj);
                }
                if(self.options.pos==true){
                    self.animation(obj, self.aPos[$(obj).attr("index")]);
                }
                self.options.cbEnd();
                if(self.options.handle!=null){
                    $(obj).find(self.options.handle).unbind("onmousemove");
                    $(obj).find(self.options.handle).unbind("onmouseup");
                }else{
                    $(obj).unbind("onmousemove");
                    $(obj).unbind("onmouseup");
                }
                obj.releaseCapture && obj.releaseCapture();
                self._start = false;

            },
            //算父级的宽高
            collTestBox: function (obj, obj2) {
                var self = this;
                var l1 = 0;
                var t1 = 0;
                var l2 = $(obj2).innerWidth() - $(obj).outerWidth();
                var t2 = $(obj2).innerHeight() - $(obj).outerHeight();
                return {
                    lmin: l1,//取的l最小值
                    tmin: t1,//取的t最小值
                    lmax: l2,//取的l最大值
                    tmax: t2//取的t最大值
                }

            },
            //算父级宽高时候干掉margin
            grid: function (obj, l, t) {//cur:[width,height]
                var self = this;
                var json = {
                    left: l,
                    top: t
                };
                if ($.isArray(self.options.grid) && self.options.grid.length == 2) {
                    var gx = self.options.grid[0];
                    var gy = self.options.grid[1];
                    json.left = Math.floor((l + gx / 2) / gx) * gx;
                    json.top = Math.floor((t + gy / 2) / gy) * gy;
                    return json
                } else if (self.options.grid == null) {
                    return json
                } else {
                    console.log("grid参数传递格式错误");
                    return false
                }
            },
            findNearest: function(obj){
                var self=this;
                var iMin=new Date().getTime();
                var iMinIndex=-1;
                var ele=self.ele;
                for(var i=0;i<ele.length;i++){
                    if(obj==ele[i]){
                        continue;
                    }
                    if(self.collTest(obj,ele[i])){
                        var dis=self.getDis(obj,ele[i]);
                        if(dis<iMin){
                            iMin=dis;
                            iMinIndex=i;
                        }
                    }
                }
                if(iMinIndex==-1){
                    return null;
                }else{
                    return ele[iMinIndex];
                }
        },
            getDis: function(obj,obj2){
                var self=this;
                var l1=obj.offsetLeft+obj.offsetWidth/2;
                var l2=obj2.offsetLeft+obj2.offsetWidth/2;

                var t1=obj.offsetTop+obj.offsetHeight/2;
                var t2=obj2.offsetTop+obj2.offsetHeight/2;

                var a=l2-l1;
                var b=t1-t2;

            return Math.sqrt(a*a+b*b);
        },
            collTest: function(obj,obj2){
                var self=this;
                var l1=obj.offsetLeft;
                var r1=obj.offsetLeft+obj.offsetWidth;
                var t1=obj.offsetTop;
                var b1=obj.offsetTop+obj.offsetHeight;

                var l2=obj2.offsetLeft;
                var r2=obj2.offsetLeft+obj2.offsetWidth;
                var t2=obj2.offsetTop;
                var b2=obj2.offsetTop+obj2.offsetHeight;

                if(r1<l2 || r2<l1 || t2>b1 || b2<t1){
                    return false;
                }else{
                    return true;
                }
        },
            //初始布局转换
            pack: function(ele,click){
                var self=this;
                self.toDisable();
                if(self.options.pos==false){
                    for (var i = 0; i < ele.length; i++) {
                        $(ele[i]).css("position", "absolute");
                        $(ele[i]).css("margin", "0");
                        self.init(ele[i]);
                    }
                }else if(self.options.pos==true) {
                    var arr = [];
                    if (self.options.random || click) {
                        while (arr.length < ele.length) {
                            var n = self.rnd(0, ele.length);
                            if (!self.finInArr(arr, n)) {//没找到
                                arr.push(n);
                            }
                        }
                    }
                    if (self.options.random == false || click != true) {
                        var n = 0;
                        while (arr.length < ele.length) {
                            arr.push(n);
                            n++
                        }
                    }

                    //如果是第二次以后随机列表，那就重新排序后再随机，因为我智商不够使，不会排了
                    if (self.firstRandom == false) {
                        var sortarr = [];
                        var n = 0;
                        while (sortarr.length < ele.length) {
                            sortarr.push(n);
                            n++
                        }
                        for (var i = 0; i < ele.length; i++) {
                            $(ele[i]).attr("index", sortarr[i]);
                            $(ele[i]).css("left", self.aPos[sortarr[i]].left);
                            $(ele[i]).css("top", self.aPos[sortarr[i]].top);
                        }
                    }

                    //布局转化
                    self.aPos = [];
                    if (self.firstRandom == false) {
                        //不是第一次
                        for (var j = 0; j < ele.length; j++) {
                            self.aPos[j] = {
                                left: ele[$(ele).eq(j).attr("index")].offsetLeft,
                                top: ele[$(ele).eq(j).attr("index")].offsetTop
                            };
                        }
                    } else {
                        //第一次
                        for (var j = 0; j < ele.length; j++) {
                            self.aPos[j] = {left: ele[j].offsetLeft, top: ele[j].offsetTop};
                        }
                    }
                    //第二个循环布局转化
                    for (var i = 0; i < ele.length; i++) {
                        $(ele[i]).attr("index", arr[i]);
                        $(ele[i]).css("left", self.aPos[arr[i]].left);
                        $(ele[i]).css("top", self.aPos[arr[i]].top);
                        $(ele[i]).css("position", "absolute");
                        $(ele[i]).css("margin", "0");
                        self.init(ele[i]);
                    }
                    self.firstRandom = false;
                }
            },
            //移动时候加class
            moveAddClass: function(obj){
                var self=this;
                var oNear=self.findNearest(obj);
                $(self.$element).removeClass(self.options.moveClass);
                if(oNear && $(oNear).hasClass(self.options.moveClass)==false){
                    $(oNear).addClass(self.options.moveClass);
                }

            },
            //给li排序
            sort: function(){
                var self=this;
                var arr_li=[];
                for (var s = 0; s < self.$element.length; s++) {
                    arr_li.push(self.$element[s]);
                }
                for ( var i = 0; i < arr_li.length; i++) {
                    for ( var j = i + 1; j < arr_li.length; j++) {
                        if(Number($(arr_li[i]).attr("index")) > Number($(arr_li[j]).attr("index"))){
                            var temp = arr_li[i];
                            arr_li[i] = arr_li[j];
                            arr_li[j] = temp;
                        }
                    }
                }
                return arr_li;
            },
            //点对点的方式换位
            pointDrag: function(obj){
                var self=this;
                //先拍序
                var oNear=self.findNearest(obj);
                if (oNear) {
                    self.animation(obj,self.aPos[$(oNear).attr("index")]);
                    self.animation(oNear, self.aPos[$(obj).attr("index")]);
                    var tmp;
                    tmp = $(obj).attr("index");
                    $(obj).attr("index", $(oNear).attr("index"));
                    $(oNear).attr("index", tmp);
                    $(oNear).removeClass(self.options.moveClass);
                } else if (self.options.changeWhen == "end") {
                    self.animation(obj, self.aPos[$(obj).attr("index")]);
                }

            },
            //排序的方式换位
            sortDrag: function(obj){
                var self=this;
                //先拍序
                var arr_li=self.sort();
                //换位置
                var oNear=self.findNearest(obj);
                    if(oNear){
                        if(Number($(oNear).attr("index"))>Number($(obj).attr("index"))){
                            //前换后
                            var obj_tmp=Number($(obj).attr("index"));
                            $(obj).attr("index",Number($(oNear).attr("index"))+1);
                            for (var i = obj_tmp; i < Number($(oNear).attr("index"))+1; i++) {
                                self.animation(arr_li[i],self.aPos[i-1]);
                                self.animation(obj,self.aPos[$(oNear).attr("index")]);
                                $(arr_li[i]).removeClass(self.options.moveClass);
                                $(arr_li[i]).attr("index",Number($(arr_li[i]).attr("index"))-1);
                            }

                        }else if(Number($(obj).attr("index"))>Number($(oNear).attr("index"))){
                            //后换前
                            var obj_tmp=Number($(obj).attr("index"));
                            $(obj).attr("index",$(oNear).attr("index"));
                            for (var i = Number($(oNear).attr("index")); i < obj_tmp; i++) {
                                self.animation(arr_li[i],self.aPos[i+1]);
                                self.animation(obj,self.aPos[Number($(obj).attr("index"))]);
                                $(arr_li[i]).removeClass(self.options.moveClass);
                                $(arr_li[i]).attr("index",Number($(arr_li[i]).attr("index"))+1);
                            }
                        }
                    }else{
                        self.animation(obj,self.aPos[$(obj).attr("index")]);
                    }

            },
            //运动函数(后期再加参数)
            animation: function(obj,json){
                var self=this;
                //考虑默认值
                var options=self.options.animation_options; /*|| {};
                options.duration=self.options.animation_options.duration || 800;
                options.easing=options.easing.duration.easing || 'ease-out';*/
                var self=this;
                var count=Math.round(options.duration/30);
                var start={};
                var dis={};
                for(var name in json){
                    start[name]=parseFloat(self.getStyle(obj,name));
                    if(isNaN(start[name])){
                        switch(name){
                            case 'left':
                                start[name]=obj.offsetLeft;
                                break;
                            case 'top':
                                start[name]=obj.offsetTop;
                                break;
                            case 'width':
                                start[name]=obj.offsetWidth;
                                break;
                            case 'height':
                                start[name]=obj.offsetHeight;
                                break;
                            case 'marginLeft':
                                start[name]=obj.offsetLeft;
                                break;
                            case 'borderWidth':
                                start[name]=0;
                                break;
                            //...
                        }
                    }
                    dis[name]=json[name]-start[name];
                }

                var n=0;

                clearInterval(obj.timer);
                obj.timer=setInterval(function(){
                    n++;
                    for(var name in json){
                        switch(options.easing){
                            case 'linear':
                                var a=n/count;
                                var cur=start[name]+dis[name]*a;
                                break;
                            case 'ease-in':
                                var a=n/count;
                                var cur=start[name]+dis[name]*a*a*a;
                                break;
                            case 'ease-out':
                                var a=1-n/count;
                                var cur=start[name]+dis[name]*(1-a*a*a);
                                break;
                        }

                        if(name=='opacity'){
                            obj.style.opacity=cur;
                            obj.style.filter='alpha(opacity:'+cur*100+')';
                        }else{
                            obj.style[name]=cur+'px';
                        }
                    }

                    if(n==count){
                        clearInterval(obj.timer);
                        options.complete && options.complete();
                    }
                },30);
        },
            getStyle: function(obj,name){
                return (obj.currentStyle || getComputedStyle(obj,false))[name];
            },
            //随机数
            rnd: function(n,m){
                return parseInt(Math.random()*(m-n)+n);
            },
            //在数组中找
            finInArr: function(arr,n){
                for(var i = 0 ; i < arr.length; i++){
                    if(arr[i] == n){//存在
                        return true;
                    }
                }
                return false;
            }
        }
    })
})(jQuery,window,document);















