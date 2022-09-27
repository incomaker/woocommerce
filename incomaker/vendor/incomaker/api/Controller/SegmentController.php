<?php

namespace Incomaker\Api\Controller;

/**
 * Description of SegmentController
 */
class SegmentController extends Controller {

    /**
     * Adds new category. isSubscription indicates classic category = false, subscription category = true.
     * 
     * @param string $category
     * @param bool $isSubscription
     * @return string
     */
    public function addCategory($category, $isSubscription) {
        return $this->curlPostQuery("segmemt/category/", "", json_encode(array("category" => $category, "isSubscription" => $isSubscription)));
    }

    /**
     * Adds new group to a category.
     * 
     * @param string $category
     * @param string $group
     * @return string
     */
    public function addGroup($category, $group) {
        return $this->curlPostQuery("segment/group/", "", json_encode(array("category" => $category, "group" => $group)));
    }

    /**
     * Adds new segment to a category and group.
     * 
     * @param string $segmentName
     * @param string $category
     * @param string $group
     * @return string
     */
    public function addSegment($segmentName, $category, $group = null) {
        return $this->curlPostQuery("segment/", "", json_encode(array("segmentName" => $segmentName, "category" => $category, "group" => $group)));
    }

    /**
     * Returns all categories with groups and segments.
     * 
     * @return string
     */
    public function getAllSegments() {
        return $this->curlGetQuery("segment/all/");
    }

    /**
     * Updates category name.
     * 
     * @param string $oldCategory
     * @param string $newCategory
     * @return string
     */
    public function updateCategory($oldCategory, $newCategory) {
        return $this->curlPutQuery("segment/category/", "", json_encode(array("oldCategory" => $oldCategory, "newCategory" => $newCategory)));
    }

    /**
     * Updates group name. Add category in which is this group.
     * 
     * @param string $oldGroup
     * @param string $newGroup
     * @param string $category
     * @return string
     */
    public function updateGroup($oldGroup, $newGroup, $category) {
        return $this->curlPutQuery("segment/group/", "", json_encode(array("oldGroup" => $oldGroup, "newGroup" => $newGroup, "category" => $category)));
    }

    /**
     * Updates segment's category. 
     * 
     * @param string $segment
     * @param string $oldCategory
     * @param string $newCategory
     * @return string
     */
    public function updateCategoryOfSegment($segment, $category) {
        return $this->curlPutQuery("segment/", "", json_encode(array("oldName" => $segment, "category" => $category)));
    }

    /**
     * Updates segment's group
     * 
     * @param string $segment
     * @param string $category
     * @param string $group
     * @return string
     */
    public function updateGroupOfSegment($segment, $group) {
        return $this->curlPutQuery("segment/", "", json_encode(array("oldName" => $segment, "group" => $group)));
    }

    /**
     * Updates segment's name
     * 
     * @param string $oldName
     * @param string $newName
     * @param string $category
     * @return string
     */
    public function updateSegmentName($oldName, $newName) {
        return $this->curlPutQuery("segment/", "", json_encode(array("oldName" => $oldName, "newName" => $newName)));
    }

    /**
     * Deletes category and all content.
     * 
     * @param string $category
     * @return string
     */
    public function deleteCategory($category) {
        return $this->curlDeleteQuery("segment/category/", "?category=" . urlencode($category));
    }

    /**
     * Deletes group and all content
     * 
     * @param string $category
     * @param string $group
     * @return string
     */
    public function deleteGroup($category, $group) {
        return $this->curlDeleteQuery("segment/group/", "?category=" . urlencode($category) . "?group=" . urlencode($group));
    }

    /**
     * Deletes segment.
     * 
     * @param string $category
     * @param string $segment
     * @return string
     */
    public function deleteSegment($category, $segment) {
        return $this->curlDeleteQuery("segment/", "?category=" . urlencode($category) . "?segment=" . urlencode($segment));
    }

}
