<!-- Tempalte: 文件、文件夹模板 -->
<script type="text/template" id="tpl_file_item">
    <div class="fc-icon">
        <!-- 文件 -->
        <% if(type == 0) { %>
        <!-- 图片类型-->
        <% if(typeof thumb !== "undefined" && thumb) { %>
        <div class="fc-img"><img src="<%= thumb %>" title="<%= name %>"/></div>
        <!-- 其他文件类型 -->
        <% } else if(typeof iconbig !== "undefined") { %>
        <img src="<%= iconbig %>" title="<%= name %>"/>
        <% } %>

        <!-- 文件夹 -->
        <% } else { %>
        <i class="file-icon <%= access == 2 ? 'o-folder-normal' : 'o-folder-lock' %>" title="<%= name %>"></i>
        <% } %>

    </div>
    <div class="file-name" title="<%= name %>"><%= name %></div>

    <!-- 文件，显示大小 -->
    <% if(type == 0) { %>
    <div class="file-desc"><%= formattedsize %></div>

    <!-- 文件夹，显示创建时间 -->
    <% } else { %>
    <div class="file-desc"><%= formattedaddtime %></div>
    <% } %>

    <div class="file-opbar">
        <!-- o-folder-mlock -->
        <a title="打开" class="<%= access == 2 ? 'o-folder-open' : 'o-folder-mlock' %>"></a>
        <i class="fc-part">|</i>
        <a title="下载" class="o-folder-down"></a>
        <i class="fc-part">|</i>
        <a class="o-folder-dropdown"></a>
    </div>
    <i class="oc-checkbox"></i>
</script>

<!-- Tempalte: 面包屑模板 -->
<script type="text/template" id="tpl_file_breadcrumb">
    <% for(var i = 0, len = breadcrumbs.length; i < len; i++){ %>
    <!-- 当前活动项 -->
    <% if(i == len - 1) { %>
    <div class="ellipsis plm prm pull-left" style="max-width:80px;" title="<%= breadcrumbs[i].name %>">
        <a href="<%= breadcrumbs[i].path %>" class="current"><%= breadcrumbs[i].name %></a>
    </div>
    <% } else { %>
    <div class="ellipsis plm prm pull-left" style="max-width:80px;" title="<%= breadcrumbs[i].name %>">
        <a href="<%= breadcrumbs[i].path %>"><%= breadcrumbs[i].name %></a>
    </div>
    <div class="pull-left">
        <i class="o-fc-level mls"></i>
    </div>
    <% } %>
    <% } %>
</script>

<!-- Tempalte: 文件操作栏模板 -->
<script type="text/template" id="tpl_file_toolbar">
    <div class="pull-left">
        <% if(multiple) { %>
        <button type="button" class="btn btn-warning" file-act="download">打包下载</button>
        <% } else { %>
        <button type="button" class="btn btn-warning" file-act="download">下载</button>
        <% if(access >= 2) { %>
        <button type="button" class="btn mlm" file-act="rename">重命名</button>
        <% } %>
        <% } %>

        <% if(access >= 2) { %>
        <button type="button" class="btn mlm" file-act="remove">删除</button>
        <% } %>
    </div>
    <div class="pull-right select-info">
        <% if(multiple) { %>
        已选中 <span class="xco xwb"><%= length %></span> 个文件，大小 <span class="xco xwb"><%= totalSize %></span>
        <% } else { %>
        已选中 <span class="xco xwb"><%= name %></span>，大小 <span class="xco xwb"><%= totalSize %></span>，<%= isFolder ?
        '创建于' : '上传于' %> <span><%= formattedaddtime %></span>
        <% } %>
    </div>
</script>

<!-- Tempalte: 文件夹操作栏模板 -->
<script type="text/template" id="tpl_folder_toolbar">
    <% if(fileCreatable) { %>
    <button type="button" class="btn btn-primary mlm" id="upload_file_btn">上传附件</button>
    <% } %>
    <div class="btn-group mlm">
        <% if(folderCreatable) { %>
        <button type="button" class="btn btn-narrow" id="add_file_btn">
            <i class="o-add-folder"></i>
            <span class="dib">新建</span>
        </button>
        <% } %>
        <!--
        <% if(fileCreatable) { %>
        <button type="button" class="btn dropdown-toggle" data-toggle="dropdown">
        <span class="caret"></span>
        </button>
        <ul class="dropdown-menu" role="menu">
        <li><a href="javascript:;">Word</a></li>
        <li><a href="javascript:;">Excel</a></li>
        <li><a href="javascript:;">PPT</a></li>
        </ul>
        <% } %>
        -->
    </div>
</script>

<!-- Template: 文件操作菜单 -->
<script type="text/template" id="tpl_file_menu">
    <% if( /txt|office|image/.test(filetype) ){ %>
    <li>
        <a href="javascript:;" file-act="open">
            <i class="o-drop-open"></i> 打开
        </a>
    </li>
    <% } %>
    <li>
        <a href="javascript:;" file-act="download">
            <i class="o-drop-down"></i> 下载
        </a>
    </li>
    <li <% if(access < 2 || !isAdministrator) { %>class="hide"<% } %>>
    <a href="javascript:;" file-act="access">
        <i class="o-drop-competence"></i> 权限
    </a>
    </li>
    <li<% if(filetype != "office" || access < 2){ %> class="disabled" <% } %>>
    <a href="javascript:;" file-act="edit">
        <i class="o-drop-edit"></i> 编辑
    </a>
    </li>
    <li>
        <a href="javascript:;" file-act="copy">
            <i class="o-drop-copy"></i> 复制
        </a>
    </li>
    <li <% if(access < 2) { %>class="disabled"<% } %>>
    <a href="javascript:;" file-act="cut">
        <i class="o-drop-scissors"></i> 剪切
    </a>
    </li>
    <li <% if(access < 2) { %>class="disabled"<% } %>>
    <a href="javascript:;" file-act="rename">
        <i class="o-drop-rename"></i> 重命名
    </a>
    </li>
    <li <% if(access < 2) { %>class="disabled"<% } %>>
    <a href="javascript:;" file-act="remove">
        <i class="o-drop-delete"></i> 删除
    </a>
    </li>
</script>

<!-- Template: 右键菜单 -->
<script type="text/template" id="tpl_context_menu">
    <li <% if(!fileCreatable) { %>class="disabled"<% } %>>
    <a href="javascript:;" file-act="upload">上传文件</a>
    </li>
    <li <% if(!folderCreatable) { %>class="disabled"<% } %>>
    <a href="javascript:;" file-act="newFolder">新建文件夹</a>
    </li>
    <li <% if(selectedFileCount <= 0) { %> class="hide" <% } %>>
    <a href="javascript:;" file-act="copy">复制</a>
    </li>
    <li <% if(selectedFileCount <= 0 || access < 2) { %> class="hide" <% } %>>
    <a href="javascript:;" file-act="cut">剪切</a>
    </li>
    <li <% if(clipboardFileCount == 0 || access < 2 || !fileCreatable) { %>class="disabled"<% } %>>
    <a href="javascript:;" file-act="paste">
        粘贴<% if(clipboardFileCount > 0) { %>(<%= clipboardFileCount %>个文件) <% } %>
    </a>
    </li>
</script>
