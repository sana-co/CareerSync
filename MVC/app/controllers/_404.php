<?php
class _404
{
    use Controller;
    public function index()
    {
        echo "<h1>Error 404: controller not found</h1>";
    }
}
