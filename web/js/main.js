var $collectionHolder;

// setup an "add a tag" link / 设置一个“添加一个标签”链接
var $addTagLink = $('<a href="#" class="add_tag_link">Add a tag</a>');
var $newLinkLi = $('<li></li>').append($addTagLink);

jQuery(document).ready(function() {
    // Get the ul that holds the collection of tags
    // 得到持有标签集合的ul
    $collectionHolder = $('ul.tags');

    // add the "add a tag" anchor and li to the tags ul
    // 添加 "添加一个标签" 锚记到标签的ul元素中
    $collectionHolder.append($newLinkLi);

    // count the current form inputs we have (e.g. 2), use that as the new
    // index when inserting a new item (e.g. 2)
    // 计算我们当前持有的表单input数量（如2），并在插入新元素（如2）时把它作为新的索引
    $collectionHolder.data('index', $collectionHolder.find(':input').length);

    $addTagLink.on('click', function(e) {
        // prevent the link from creating a "#" on the URL
        // 防止链接在URL中出现一个 "#"
        e.preventDefault();

        // add a new tag form (see next code block)
        // 添加一个新的tag表单（参考后面的码段）
        addTagForm($collectionHolder, $newLinkLi);
    });
});

function addTagForm($collectionHolder, $newLinkLi) {
    // Get the data-prototype explained earlier
    // 获取前文解释过的data-prototype
    var prototype = $collectionHolder.data('prototype');

    // get the new index
    // 获取新的索引
    var index = $collectionHolder.data('index');

    // Replace '__name__' in the prototype's HTML to
    // instead be a number based on how many items we have
    // 在prototype的HTML中替换 '__name__'，令其成为一个基于“我们持有多少元素”的数字
    var newForm = prototype.replace(/__name__/g, index);

    // increase the index with one for the next item
    // 下一个元素要增加一个索引
    $collectionHolder.data('index', index + 1);

    // Display the form in the page in an li, before the "Add a tag" link li
    // 在页面中的 "Add a tag" 链接前面，以li来显示表单
    var $newFormLi = $('<li></li>').append(newForm);
    $newLinkLi.before($newFormLi);
}