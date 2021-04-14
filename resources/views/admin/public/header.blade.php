<div class="layui-header">
    <ul class="layui-nav layui-layout-left">
        <li class="layui-nav-item layadmin-flexible" lay-unselect>
            <a href="javascript:;" layadmin-event="flexible" title="侧边伸缩">
                <i class="layui-icon layui-icon-shrink-right" id="LAY_app_flexible"></i>
            </a>
        </li>
{{--        <li class="layui-nav-item layui-hide-xs" lay-unselect>--}}
{{--            <a href="http://www.layui.com/admin/" target="_blank" title="前台">--}}
{{--                <i class="layui-icon layui-icon-website"></i>--}}
{{--            </a>--}}
{{--        </li>--}}
        <li class="layui-nav-item" lay-unselect>
            <a href="javascript:;" layadmin-event="refresh" title="刷新">
                <i class="layui-icon layui-icon-refresh-3"></i>
            </a>
        </li>
        <li class="layui-nav-item layui-hide-xs" lay-unselect>
            <input type="text" placeholder="搜索..." autocomplete="off" class="layui-input layui-input-search" layadmin-event="serach" lay-action="template/search.html?keywords=">
        </li>
    </ul>
    <ul class="layui-nav layui-layout-right" lay-filter="layadmin-layout-right" style="margin-right: 15px;">

{{--        <li class="layui-nav-item" lay-unselect>--}}
{{--            <a lay-href="app/message/index.html" layadmin-event="message" lay-text="消息中心">--}}
{{--                <i class="layui-icon layui-icon-notice"></i>--}}

{{--                <!-- 如果有新消息，则显示小圆点 -->--}}
{{--                <span class="layui-badge-dot"></span>--}}
{{--            </a>--}}
{{--        </li>--}}
        <li class="layui-nav-item layui-hide-xs" lay-unselect>
            <a href="javascript:;" layadmin-event="theme">
                <i class="layui-icon layui-icon-theme"></i>
            </a>
        </li>
{{--        <li class="layui-nav-item layui-hide-xs" lay-unselect>--}}
{{--            <a href="javascript:;" layadmin-event="note">--}}
{{--                <i class="layui-icon layui-icon-note"></i>--}}
{{--            </a>--}}
{{--        </li>--}}
        <li class="layui-nav-item layui-hide-xs" lay-unselect>
            <a href="javascript:;" layadmin-event="fullscreen">
                <i class="layui-icon layui-icon-screen-full"></i>
            </a>
        </li>
        <li class="layui-nav-item" lay-unselect>
            <a href="javascript:;">
                <cite>{{Auth::user()->username}}</cite>
            </a>
            <dl class="layui-nav-child">
                <dd><a lay-href="{{url('admin/manager/my_info')}}">修改资料</a></dd>
                <dd><a lay-href="{{url('admin/manager/my_pwd')}}">修改密码</a></dd>
                <hr>
                <dd style="text-align: center;" class="layui-form"><a href="javascript:" lay-submit lay-filter="admin_manager_logout">退出</a></dd>
            </dl>
        </li>
    </ul>
</div>

