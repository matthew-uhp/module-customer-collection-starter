# SwiftOtter_CustomerCollection

Starter module for a "My Collection" feature allowing customers to save a collection of products they own.

**This module is intended as a starter for the final hands-on exercise of the SwiftOtter Magento 101 training course.**

## The Scenario

Your online store sells products that are collectible in nature. While wishlists give customers the ability to track 
what collectible items they want, your customers also like to keep track of the items already in their collection and to share
the collection with others in the community. You want to give customers a way to manage their collection directly on
your site.

Eventually, you want customers to have the capability of sharing collections on social media, posting images of their own
unique customizations to their items, and arranging their items into different sets. For the initial features, however,
you only require the following:

* On product detail pages, logged-in customers have the ability to add the product to My Collection along with a comment.
* In My Account, a "My Collection" link will navigate customers to a dedicated page.
* The "My Collection" page will list the names of the products in the customer's collection, along with their own comments.

## The Boilerplate

The following are already done for you in this module:

* Appropriate registration files are in place allowing `SwiftOtter_CustomerCollection` to be enabled.
* A `system.xml` is in place, adding a new My Collection group to the settings in Stores > Configuration > Customers > 
  Customer Configuration.
* Schema is in place creating the database table `customer_collection_item` with fields for customer ID, product ID, and
  created/updated timestamps.
* A `catalog_product_view.xml` layout file has been bootstrapped with a reference to the container where the "add to
  collection" block should be added.
* The `SwiftOtter\CustomerCollection\ViewModel\Product\View\CollectionAdd` view model already exists, providing the data
  needed by the "Add to My Collection" block.
* A controller is bootstrapped for the URL path /mycollection/item/add, which the "Add to My Collection" form should POST to.
* A "My Collection" link is added to the left-hand navigation in My Account, linking to the URL path /mycollection.
* The controller for the URL path /mycollection is bootstrapped.
* A starting layout file for the "My Collection" page exists (`rename_me.xml`), with a top-level block, a `cacheable` attribute to avoid
  the page being cached, and the appropriate update to include the My Account navigation.
* The `SwiftOtter\CustomerCollection\ViewModel\Customer\CollectionList` view model exists, with functionality in place
  for efficiently loading the products associated with collection items (once `getCollectionItems` is completed) and
  fetching a product from that list.
