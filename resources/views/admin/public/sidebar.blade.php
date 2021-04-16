<div class="layui-side layui-side-menu">
    <div class="layui-side-scroll">
        <div class="layui-logo" lay-href="/admin/homepage">
            <span>众辉车联</span>
        </div>

        <ul class="layui-nav layui-nav-tree" lay-shrink="all" id="LAY-system-side-menu" lay-filter="layadmin-system-side-menu">
            @if(isset($userMenus))
                @foreach($userMenus as $menu1)
                    <li @if(isset($menu1->active))class="layui-nav-item layui-nav-itemed"@else class="layui-nav-item"@endif>
                        <a href="javascript:;" lay-tips="{{$menu1->name}}" lay-direction="2">
                            <i class="@if($menu1->icon){{$menu1->icon}}@endif"></i>
                            <cite>{{$menu1->name}}</cite>
                        </a>
                        @if(isset($menu1->children))
                            <dl class="layui-nav-child" style="padding-left: 15px;">
                                @foreach($menu1->children as $menu2)
                                    <dd data-name="console" @if(isset($menu2->active))class="layui-this"@endif>
                                        <a lay-href="/admin{{$menu2->url}}">
                                            <i class="fa @if($menu2->icon){{$menu2->icon}}@endif"></i>
                                            <cite>{{$menu2->name}}</cite>
                                        </a>
                                    </dd>
                                @endforeach
                            </dl>
                        @endif
                    </li>
                @endforeach
            @endif

        </ul>
    </div>
</div>
