<?php
function test(){
return '
<div id="qunit"></div>
  <div id="qunit-fixture"></div>
  <script src="http://code.jquery.com/qunit/qunit-1.18.0.js"></script>
  <script>
QUnit.test( "hello test", function( assert ) {
  assert.ok( 1 == "1", "Passed!" );
});
</script>
  <h1>holaa</h1>
';
}
