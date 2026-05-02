@extends('layouts.app')

@section('title', 'HSPC - ACM @ UVA')

@section('content')
	<h1 class="center-on-mobile">HSPC</h1>
    <p class="center-on-mobile">Every year, the largest High School Programming Contest (HSPC) in the Mid-Atlantic region takes place here at UVA.</p>
    <nav class="mt-3 flex flex-col md:flex-row gap-4 justify-center md:justify-start items-center mb-4" aria-label="Quick links">
        <a class="btn btn-primary flex items-center gap-2" href="https://docs.google.com/document/d/1SqNWaz4f7bvELBaL43CsFzSB0lH9HEygats-4vima-4/edit" target="_blank" rel="noopener" aria-label="HSPC Information Packet">
          <i class="fa fa-info-circle me-2"></i> Information
        </a>
        <a href="https://uvahspc2026.eventbrite.com/" target="_blank" rel="noopener" class="btn btn-secondary flex items-center gap-2" aria-label="Register">
          <i class="fa-solid fa-user-plus fa-lg"></i> Register
        </a>
        <a href="mailto:hspc@virginia.edu" class="btn btn-accent flex items-center gap-2" aria-label="Contact ACM Officers">
          <i class="fa-solid fa-envelope fa-lg"></i> Contact
        </a>
      </nav>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div>
            <h2>Frequently Asked Questions</h2>
            <div class="join join-vertical bg-base-100">
                <div class="collapse collapse-arrow join-item border-base-300 border">
                    <input type="radio" name="hspc-faq-accordion" />
                    <div class="collapse-title font-semibold">What are the contest rules?</div>
                    <div class="collapse-content text-sm">
                        <p>Rules can be found in the Logistics section of our <a href="https://docs.google.com/document/d/1SqNWaz4f7bvELBaL43CsFzSB0lH9HEygats-4vima-4/edit">information packet</a>.</p>
                        <p>For further comments or questions, please email <a href="mailto:hspc@virginia.edu">hspc@virginia.edu</a>.</p>
                    </div>
                </div>
                <div class="collapse collapse-arrow join-item border-base-300 border">
                    <input type="radio" name="hspc-faq-accordion" />
                    <div class="collapse-title font-semibold">What are the versions of software used?</div>
                    <div class="collapse-content text-sm">
                        <p>Competitors will have access to the following IDEs:</p>
                        <ul>
                            <li>Eclipse 4.13</li>
                            <li>IntelliJ IDEA Community Edition 2022.3</li>
                            <li>CLion 2022.3</li>
                            <li>PyCharm Community Edition 2022.3</li>
                            <li>Code::Blocks 20.03-3.1</li>
                            <li>Visual Studio Code 1.74.2</li>
                        </ul>
                        <p>Our judging software will use the following language versions:</p>
                        <ul>
                            <li>gcc/g++ 13.3.0</li>
                            <li>Python 3 (PyPy version 7.3.15 providing Python 3.9.18)</li>
                            <li>Java 17 (openjdk version 21.0.7)</li>
                            <li>Kotlin 1.9.24</li>
                        </ul>
                        <p>We will be using DomJudge to submit and judge solutions.</p>
                    </div>
                </div>
                <div class="collapse collapse-arrow join-item border-base-300 border">
                    <input type="radio" name="hspc-faq-accordion" />
                    <div class="collapse-title font-semibold">Are there any sample problems available?</div>
                    <div class="collapse-content text-sm">
                        <p>You can read past problem sets (PDF) and see full solutions with sample and judge input/output (zip) here:</p>
                        <ul class="list-unstyled">
                            <li>2026 Div. 1 (<a href="/data/2026-d1-contest.pdf">problems</a>)&nbsp;(<a href="/data/2026-d1-solutions.pdf">solutions</a>)</li>
                            <li>2026 Div. 2 (<a href="/data/2026-d2-contest.pdf">problems</a>)&nbsp;(<a href="/data/2026-d2-solutions.pdf">solutions</a>)</li>
                            <li>2025 (<a href="/data/2025-problems.zip">zip</a>)&nbsp;(<a href="/data/2025-contest.pdf">pdf</a>)</li>
                            <li>2024 (zip coming soon)&nbsp;(<a href="/data/2024-contest.pdf">pdf</a>)</li>
                            <li>2023 (zip coming soon)&nbsp;(<a href="/data/2023-contest.pdf">pdf</a>)</li>
                            <li>2022 (zip coming soon)&nbsp;(<a href="/data/2022-contest.pdf">pdf</a>)</li>
                            <li>2021 (zip coming soon)&nbsp;(<a href="/data/2021-contest.pdf">pdf</a>)</li>
                            <li>2019 (<a href="/data/2019-problems.zip">zip</a>)&nbsp;(<a href="/data/2019-contest.pdf">pdf</a>)</li>
                            <li>2018 (<a href="/data/2018-problems.zip">zip</a>)&nbsp;(<a href="/data/2018-contest.pdf">pdf</a>)</li>
                            <li>2017 (<a href="/data/2017-problems.zip">zip</a>)&nbsp;(<a href="/data/2017-contest.pdf">pdf</a>)</li>
                            <li>2016 (<a href="/data/2016-problems.zip">zip</a>)&nbsp;(<a href="/data/2016-contest.pdf">pdf</a>)</li>
                            <li>2015 (<a href="/data/2015-problems.zip">zip</a>)&nbsp;(<a href="/data/2015-contest.pdf">pdf</a>)</li>
                            <li>2014 (<a href="/data/2014-problems.zip">zip</a>)&nbsp;(<a href="/data/2014-contest.pdf">pdf</a>)</li>
                            <li>2013 (<a href="/data/2013-problems.zip">zip</a>)&nbsp;(<a href="/data/2013-contest.pdf">pdf</a>)</li>
                            <li>2012 (<a href="/data/2012-problems.zip">zip</a>)&nbsp;(<a href="/data/2012-contest.pdf">pdf</a>)</li>
                            <li>2011 (<a href="/data/2011-problems.zip">zip</a>)&nbsp;(<a href="/data/2011-contest.pdf">pdf</a>)</li>
                        </ul>
                    </div>
                </div>
                <div class="collapse collapse-arrow join-item border-base-300 border">
                    <input type="radio" name="hspc-faq-accordion" />
                    <div class="collapse-title font-semibold">What reference materials will be available?</div>
                    <div class="collapse-content text-sm">
                        <p>We will announce this information closer to the competition. It will likely include language reference documentation.</p>
                    </div>
                </div>
                <div class="collapse collapse-arrow join-item border-base-300 border">
                    <input type="radio" name="hspc-faq-accordion" />
                    <div class="collapse-title font-semibold">What are the prizes like?</div>
                    <div class="collapse-content text-sm">
                        <p>Prizes are likely to include Amazon gift cards, board games, drones, and other similarly useful items.</p>
                    </div>
                </div>
                <div class="collapse collapse-arrow join-item border-base-300 border">
                    <input type="radio" name="hspc-faq-accordion" />
                    <div class="collapse-title font-semibold">What are the past results?</div>
                    <div class="collapse-content text-sm">
                        <p>Past scoreboards are available for the following HSPC events:</p>
                        <ul>
                            <li><a href="/scoreboards/2025">2025</a></li>
                            <li><a href="/scoreboards/2024">2024</a></li>
                            <li><a href="/scoreboards/2023">2023</a></li>
                            <li><a href="/scoreboards/2019">2019</a></li>
                            <li><a href="/scoreboards/2018">2018</a></li>
                            <li><a href="/scoreboards/2017">2017</a></li>
                            <li><a href="/scoreboards/2016">2016</a></li>
                            <li><a href="/scoreboards/2015">2015</a></li>
                            <li><a href="/scoreboards/2014">2014</a></li>
                            <li><a href="/scoreboards/2013">2013</a></li>
                            <li><a href="/scoreboards/2012">2012</a></li>
                        </ul>
                    </div>
                </div>
                <div class="collapse collapse-arrow join-item border-base-300 border">
                    <input type="radio" name="hspc-faq-accordion" />
                    <div class="collapse-title font-semibold">What is the computer configuration for the contest?</div>
                    <div class="collapse-content text-sm">
                        <p>Each team will have access to a single Linux environment with common IDEs, compilers, and editors installed. This computer will be shared by all team members.</p>
                    </div>
                </div>
                <div class="collapse collapse-arrow join-item border-base-300 border">
                    <input type="radio" name="hspc-faq-accordion" />
                    <div class="collapse-title font-semibold">Who can I contact if I still have questions?</div>
                    <div class="collapse-content text-sm">
                        <p>If you have any questions, please contact the HSPC contest director (<a href="mailto:hspc@virginia.edu">hspc@virginia.edu</a>).</p>
                    </div>
                </div>
                <div class="collapse collapse-arrow join-item border-base-300 border">
                    <input type="radio" name="hspc-faq-accordion" />
                    <div class="collapse-title font-semibold">Where do the teams come from?</div>
                    <div class="collapse-content text-sm">
                        <p>Teams come from all over Virginia. Some even come from Maryland once in a while.</p>
                    </div>
                </div>
            </div>
        </div>
        <div>
            <h2>Our Plan for 2026</h2>
            <p>This year, we plan to host HSPC in person at UVA! All the information needed for the event can be found in the packet linked <a href="https://docs.google.com/document/d/1SqNWaz4f7bvELBaL43CsFzSB0lH9HEygats-4vima-4/edit">here</a>.</p>
            <h2>Want to help out?</h2>
            <p>HSPC is run by UVA students! If you are a current or graduated UVA student and want to help inspire high school students, let us know at <a href="mailto:hspc@virginia.edu">hspc@virginia.edu</a> or join our <a href="https://discord.gg/wxWgbVs">Discord server</a> and type <code>!hspc</code>. Thank you! </p>
        </div>
        <div>
            <h2>Sponsorship</h2>
            <p>
                If you are interested in sponsoring the contest, and encouraging high school students to pursue computing, please email <a href="mailto:hspc@virginia.edu">hspc@virginia.edu</a>.
            </p>
            <h2>Registration</h2>
            <div class="join join-vertical bg-base-100">
                <div class="collapse collapse-arrow join-item border-base-300 border">
                    <input type="radio" name="hspc-registration-accordion" />
                    <div class="collapse-title font-semibold">Where do I register?</div>
                    <div class="collapse-content text-sm">
                        <p>Previous year's coaches should receive an email from us asking about registration. If you do not receive an email or are a new team interested in participating, please register <a href="https://uvahspc2026.eventbrite.com/">here</a>.</p>
                    </div>
                </div>
                <div class="collapse collapse-arrow join-item border-base-300 border">
                    <input type="radio" name="hspc-registration-accordion" />
                    <div class="collapse-title font-semibold">What do I need to know about registration?</div>
                    <div class="collapse-content text-sm">
                        <p>Each team consists of up to three students and one coach. Registration costs $40 per team. Each school may register up to three teams.</p>
                    </div>
                </div>
                <div class="collapse collapse-arrow join-item border-base-300 border">
                    <input type="radio" name="hspc-registration-accordion" />
                    <div class="collapse-title font-semibold">Is financial assistance available?</div>
                    <div class="collapse-content text-sm">
                        <p>We know that the cost of attendance for our competition may be prohibitive for some schools. If your school is interested in participating but is prevented by the cost, please let our HSPC Contest Chair know by email at <a href="mailto:hspc@virginia.edu">hspc@virginia.edu</a>.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
