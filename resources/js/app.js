import "./bootstrap";
import Search from "./live-search";

//alert("This already refreshed...");
if (document.querySelector(".header-search-icon")) {
    new Search();
}
