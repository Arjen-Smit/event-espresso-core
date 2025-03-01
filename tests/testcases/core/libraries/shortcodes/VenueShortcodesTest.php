<?php


/**
 * EE_VenueShortcodes_Test
 * Test-cases for the messages system venue shortcodes parser.
 *
 * @package EventEspresso\tests
 * @author  Darren Ethier
 * @since   4.9.39.rc.054
 * @group   messages
 * @group   messages_shortcodes
 * @group   10726
 */
class VenueShortcodesTest extends EE_UnitTestCase
{

    /**
     * @var EE_Venue_Shortcodes;
     */
    protected $parser;


    public function set_up()
    {
        parent::set_up();
        $this->parser = new EE_Venue_Shortcodes();
    }


    public function tear_down()
    {
        parent::tear_down();
        $this->parser = null;
    }


    /**
     *
     * @return array
     */
    protected function getVenueTestValues()
    {
        return [
            'VNU_name'     => 'Macadassy Hall',
            'VNU_address'  => '10th St. & Constitution Ave. NW',
            'VNU_city'     => 'Washington',
            'STA_ID'       => 10, //District of Columnbia
            'CNT_ISO'      => 'US', //United States
            'VNU_zip'      => '20560',
            'VNU_desc'     => 'test_description',
            'VNU_url'      => 'https://testvenue.com',
            'VNU_phone'    => '123-123-1234',
            'VNU_address2' => '',
        ];
    }


    /**
     *  Data provider for tests.
     *
     * @return array
     */
    public function venueShortcodeValuesProvider()
    {
        $testData       = $this->getVenueTestValues();
        $google_map_url = htmlentities2(
            'https://maps.google.com/maps?q=' . urlencode(
                $testData['VNU_address']
                . ',' . $testData['VNU_city']
                . ',' . 'District of Columbia'
                . ',' . $testData['VNU_zip']
                . ',' . 'United States'
            )
        );
        return [
            ['[VENUE_TITLE]', $testData['VNU_name']],
            ['[VENUE_DESCRIPTION]', $testData['VNU_desc']],
            ['[VENUE_URL]', $testData['VNU_url']],
            ['[VENUE_PHONE]', $testData['VNU_phone']],
            ['[VENUE_ADDRESS]', $testData['VNU_address']],
            ['[VENUE_ADDRESS2]', $testData['VNU_address2']],
            ['[VENUE_CITY]', $testData['VNU_city']],
            ['[VENUE_COUNTRY]', 'United States'],
            ['[VENUE_STATE]', 'District of Columbia'],
            ['[VENUE_ZIP]', $testData['VNU_zip']],
            [
                '[VENUE_FORMATTED_ADDRESS]',
                '<div itemprop="address" itemscope itemtype="http://schema.org/PostalAddress"><span itemprop="streetAddress">10th St. & Constitution Ave. NW</span><br /><span itemprop="addressLocality">Washington</span><br /><span itemprop="addressRegion">District of Columbia</span><br /><span itemprop="postalCode">20560</span><br /><span itemprop="addressCountry">United States</span></div>',
            ],
            ['[GOOGLE_MAP_URL]', $google_map_url],
            [
                '[GOOGLE_MAP_LINK]',
                '<a href="' . $google_map_url . '"'
                . ' target="_blank" rel="noopener noreferrer">'
                . $google_map_url
                . '</a>',
            ],
        ];
    }


    /**
     * Returns an Event object for use by the Venue Shortcodes library tests.
     * return EE_Event
     */
    protected function getDataForShortcodes()
    {
        //setup a venue for the event for our tests.
        $venue = $this->factory->venue->create($this->getVenueTestValues());
        /** @var EE_Event $event */
        $event = $this->factory->event->create();
        $event->_add_relation_to($venue, 'Venue');
        $event->save();
        return $event;
    }


    /**
     * @dataProvider venueShortcodeValuesProvider
     * @param string $shortcode_tested
     * @param string $expected
     */
    public function testShortcodes($shortcode_tested, $expected)
    {
        $this->assertEquals($expected, $this->parser->parser($shortcode_tested, $this->getDataForShortcodes()));
    }

}