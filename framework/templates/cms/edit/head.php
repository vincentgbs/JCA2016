<p>
    <br>This page will allow you to edit html, css and js templates. <br>
    Filenames are restricted to alphanumerics and underscores. <br>
    To create a new template, enter the name and click the corresponding html, css or js button.<br>
    Html templates make up pages. Css and js templates are used within the pages. <br>
    Css and js templates can be referenced through:
    <ul>
        <li>&lt;link rel="stylesheet" type="text/css" href="/cache/css/style.css"&gt;</li>
        <li>&lt;script type="text/javascript" src="/cache/js/vanilla.js"&gt;&lt;/script&gt;</li>
    </ul>
    After creating a template here, you can add it to your page using the "Edit Pages" page. <br>
    <br>
    Special pages (index, events, sermons) cannot be viewed on the "Edit Pages" page.<br>
    The templates can still be edited from this page. <br>
    The standard template order and naming convention is: <br>
    <ul>
        <li>Page name: example</li>
        <li>1. headtop - shared between all pages</li>
        <li>2. headexample - page specific css and js</li>
        <li>3. headbot - shared between all pages</li>
        <li>exampletop - page body (part above the looped content)</li>
        <li>exampleloopeditem - page body (the looped content: content tags {{{@data:content}}})</li>
        <li>examplebot - page body (part below the looped content)</li>
        <li>footer - shared between all pages</li>
    </ul>
</p>
<hr class="row col-md-12">
Resource name: <input type="text" id="resource_name">
<button class="btn btn-success get_resource_button"
    title="click to create a new template"
    type="html">html</button>
<button class="btn btn-default get_resource_button"
    type="css">css</button>
<button class="btn btn-default get_resource_button"
    type="js">js</button>
<style>
</style>

<div id="resource_container" class="row col-md-12">
