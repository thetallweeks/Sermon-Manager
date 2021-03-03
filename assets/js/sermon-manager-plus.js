jQuery(document).ready(function($) {
  var key = 'data-section';
  var $sections = $('.smp-content-section');
  var $filterLinks = $('.smp-filters a');
  var $filterTabs = $('.smp-filters li');

  function setVisibleSection(sectionId) {
    $sections.each(function(index, section) {
      var $section = $(section);
      var _sectionId = section.id;

      if (_sectionId === sectionId) {
        $section.removeClass('hidden');
      } else {
        $section.addClass('hidden');
      }
    });
  }

  function setActiveFilter(sectionId) {
    $filterTabs.each(function(index, tab) {
      var $tab = $(tab);
      var _sectionId = $tab
        .find('[' + key + ']')
        .first()
        .attr(key);

      if (_sectionId === sectionId) {
        $tab.addClass('active');
      } else {
        $tab.removeClass('active');
      }
    });
  }

  // Attach click event for filters
  $filterLinks.click(function(e) {
    var sectionId = $(this).attr(key);

    if (sectionId) {
      setVisibleSection(sectionId);
      setActiveFilter(sectionId);
    }
  });

  // Initialize active section/filter
  var initialSectionId = 'all-sermons';

  // NOTE: If initialSectionId is not all-sermons, this code can
  // be uncommented to handle pagination
  // /page/ indicates we are paginating, which is only present in all sermons
  // if (window && window.location) {
  //   if (window.location.pathname.includes('/page/')) {
  //     initialSectionId = 'all-sermons';
  //   }
  // }

  setVisibleSection(initialSectionId);
  setActiveFilter(initialSectionId);
});
