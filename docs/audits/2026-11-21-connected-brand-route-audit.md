# Connected Brands route audit

The agency-team edit route referred to an absent controller method. It now checks team-management permission, validates the brand and active assignment, updates only supplied access or note fields, and records a before-and-after activity entry. Omitted permissions and venue or shift assignments stay intact. Isolated tests cover ordinary-user rejection, mismatched brands, historical assignments and a successful notes-only update.

The retained Merchandiser roster URL now opens the current Admin Hub. Route reflection verifies all served Brands and Merchandiser Admin actions. Production verified 91 controller actions and rendered the roster with an existing administrator profile. No live user assignment was edited during verification.

The full suite passed 455 tests and 3328 assertions. Nine clock-in tests initially used the current Saturday while expecting a scheduled weekday; their shared fixture now pins a known Wednesday. Operational working-day and clock-in rules were not weakened.

The staff portal's older Brands controller is a separate retained copy behind split-app routing. The fixes were applied to this actual Brands deployment rather than replacing it with that older controller.
