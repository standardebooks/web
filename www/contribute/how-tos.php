<?
require_once('Core.php');
?><?= Template::Header(['title' => 'How-to Guides For Difficult Productions', 'manual' => true, 'highlight' => 'contribute', 'description' => 'Guides on how to produce more difficult productions.']) ?>
<main class="manual">
	<article class="step-by-step-guide">
    <h1>How-to Guides For Difficult Productions</h1>
    <p>This section is for guides to help produce ebooks that contain complex formatting.</p>
    <section>
      <h2>How to Produce a Shakespeare Play</h2>
      <p>William Shakespeare’s plays are notoriously complex. Everything from archaic language to unusual play formatting can make producing one of his plays quite scary. Don’t panic! This is written to help guide any brave soul through the MIT transcriptions and William George Clark and William Aldis Wright’s 1887 Victoria Edition.</p>
      <ol>
        <li>
          <h3>Sources</h3>
          <ol>
            <li>
              <h4>Transcriptions</h4>
              <p>SE’s productions of William Shakespeare’s plays are based on <a href="http://shakespeare.mit.edu/index.html">Massachusetts Institute of Technology’s transcriptions produced by Jeremy Hylton</a>.</p>
            </li>
            <li>
              <h4>Page Scans</h4>
              <p>When it comes to Shakespeare’s plays, there are infinite page scans available, but each edition can differ in stage directions, stage direction formatting, punctuation, spelling, personas, and even dialogue. For SE’s productions, <a href="https://catalog.hathitrust.org/Record/004135080"><i>William George Clark and William Aldis Wright’s 1887 Victoria Edition</i></a> is used as the gold standard.</p>
            </li>
          </ol>
        </li>
        <li>
          <h3>Dramatis Personaes</h3>
          <ul>
            <li>
              <p>The MIT transcriptions do not include the dramatis personaes and must be restored.</p>
            </li>
            <li>
              <p>Follow <a href="https://standardebooks.org/manual/1.1.1/7-high-level-structural-patterns#7.6.6.4">section 7.6.6.4 in the manual</a> for basic file structure. Adjust letter case of personas to follow sentence case. Remove periods.</p>
            </li>
            <li>
              <p>The scene description is placed in a <code class="html"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span></code> element. Note that the description is followed by a period.</p>
            </li>
          </ul>
        </li>
        <li>
          <h3>Prologues</h3>
          <ol>
            <li>
              <h4>Locations</h4>
              <ul>
                <li>
                  <p>Prologues can appear at the beginning of the whole play and within certain acts.</p>
                </li>
                <li>
                  <p>Look at <a href="https://github.com/standardebooks/william-shakespeare_henry-v"><i>Henry V</i></a> and <a href="https://github.com/standardebooks/william-shakespeare_romeo-and-juliet"><i>Romeo and Juliet</i></a> for examples.</p>
                </li>
              </ul>
            </li>
            <li>
              <h4>Structure</h4>
              <ul>
                <li>
                  <p>Prologues follow a verse structure. See <a href="https://standardebooks.org/manual/1.1.1/7-high-level-structural-patterns#7.5">section 7.5 in the manual</a>. Hint: the first letter of a verse line is capitalized.</p>
                </li>
              </ul>
            </li>
            <li>
              <h4>Chorus or No Chorus</h4>
              <ul>
                <li>
                  <p>The decision on whether or not the Prologue contains a chorus persona and a stage direction is dependent on the page scans.</p>
                </li>
              </ul>
            </li>
            <li>
              <h4>Indents</h4>
              <ul>
                <li>
                  <p>Some Prologues have indents, some don’t. Assume the indents begin at level “i1.”</p>
                </li>
                <li>
                  <p>Be careful. Because the plays are shoved into two columns in the page scans, a verse line will spill over onto another line. This second line is indented again. We don’t want this separation. Make sure the verse line is together, and the indent is only applied to the beginning of the verse line.</p>
                </li>
              </ul>
            </li>
          </ol>
        </li>
        <li>
          <h3>Acts and Scenes</h3>
          <p>Overall file structuring and styling can be found in <a href="https://standardebooks.org/manual/1.1.1/7-high-level-structural-patterns#7.6.6">section 7.6.6 in the manual</a>.</p>
          <ol>
            <li>
              <h4>Scene Descriptions</h4>
              <ul>
                <li>
                  <p>Each scene has a scene description. Any personas named in the scene description need to be wrapped in a <code class="html"><span class="p">&lt;</span><span class="nt">b epub:type="z3998:persona"</span><span class="p">&gt;</span></code>.</p>
                </li>
              </ul>
            </li>
            <li>
              <h4>Stage Directions</h4>
              <ol>
                <li>
                  <h5>MIT Transcription Stage Directions</h5>
                  <ul>
                    <li>
                      <p>The MIT transcriptions do not distinguish between “major” and “minor” stage directions. This will need to be addressed in the production.</p>
                    </li>
                    <li>
                      <p>The transcriptions also removed all of the periods from the stage directions. Look at the page scans to see whether the stage direction contains a period.</p>
                    </li>
                  </ul>
                </li>
                <li>
                  <h5>“Major” Stage Directions</h5>
                  <ul>
                    <li>
                      <p>This is referring to the stage directions that are shown appear on a separate line and centered in the page scans. The names of main characters are capitalized.</p>
                    </li>
                    <li>
                      <p>See <a href="https://standardebooks.org/manual/1.1.1/7-high-level-structural-patterns#7.6.5">section 7.6.5 in the manual</a> for stage direction and persona semantics and styling.</p>
                    </li>
                    <li>
                      <p>You don’t have to center these directions.</p>
                    </li>
                  </ul>
                </li>
                <li>
                  <h5>“Minor” Stage Directions</h5>
                  <ul>
                    <li>
                      <p>This is referring to the stage directions that are surrounded by brackets in the page scans.</p>
                    </li>
                    <li>
                      <p>Wrap “minor” directions with the same semantics as “major” stage directions.</p>
                    </li>
                    <li>
                      <p>Instead of right-aligning the stage directions, we add a space and place it behind the previous verse line.</p>
                    </li>
                  </ul>
                </li>
                <li>
                  <h5>Personas in Stage Directions</h5>
                  <ul>
                    <li>
                      <p>Personas that are in small caps in “major” stage directions are also in small caps in “minor” stage directions.</p>
                    </li>
                    <li>
                      <p>There are some minor characters mentioned in the stage directions and are italicized in the page scans. Keep them italicized in the production.</p>
                    </li>
                    <li>
                      <p>There are some minor personas that appear as neither italics or small caps in the page scans. The decision on whether the persona is in italics or small caps depends on whether that persona has dialogue. If the character has dialogue then their name is put into small caps.</p>
                    </li>
                  </ul>
                </li>
              </ol>
            </li>
            <li>
              <h4>Personas and Their Dialogue</h4>
              <ul>
                <li>
                  <p>In the page scans, the personas are abbreviated. These need to be expanded.</p>
                </li>
                <li>
                  <p>The first line containing the persona and the beginning of dialogue is indented. Ignore this indent.</p>
                </li>
                <li>
                  <p>The MIT transcriptions capitalize the personas. Revert the personas to name case.</p>
                </li>
              </ul>
            </li>
            <li>
              <h4>Prose vs. Verse</h4>
              <ul>
                <li>
                  <p>For the purpose of Shakespeare’s plays, we are going to use verse semantics for file structuring purposes rather than labeling meters within text. Counting iambic pentameter is a cruel and unusual punishment.</p>
                </li>
                <li>
                  <p>Whether it is in iambic pentameter or not, if the dialogue <em>is not</em> broken into rows we treat it as “prose.”</p>
                </li>
                <li>
                  <p>If the dialogue <em>is</em> broken into rows beginning with a capital letter we treat it as “verse.” See <a href="https://standardebooks.org/manual/1.1.1/7-high-level-structural-patterns#7.5">section 7.5 in the manual</a>.</p>
                </li>
                <li>
                  <p>Be careful. Because the plays are shoved into two columns in the page scans, a verse line will spill over onto another line. This second line is indented. We don’t want this separation. Make sure the verse line is together.</p>
                </li>
              </ul>
            </li>
            <li>
              <h4>Right-aligned Verse and Prose Lines</h4>
              <ul>
                <li>
                  <p>Converting right-aligned to indents is too complicated. Is the size of the indent measured from the left margin of the column to the beginning of the line? Should the indent be the distance between the persona and the beginning of the line? Could these indents be standardized? How do we relate right-aligned words followed by extra spaces to indents? Why are middle verse lines right aligned? Why are the spaces in between words not the same size? What is the standard space size? Why did I choose Shakespeare? All of these factors resulted in using over 15 different levels of indenting (depending on how you measure), lines looking like they are right aligned (looks weird on large screens), and a terrible drinking habit. I don’t see how we can stay faithful to Wright and Clark’s 1887 Edition if every producer is measuring indents a different way.</p>
                </li>
                <li>
                  <p>Treat these lines as if they are not right-aligned.</p>
                </li>
              </ul>
            </li>
            <li>
              <h4>Songs</h4>
              <ol>
                <li>
                  <h5>Titles and Labels</h5>
                  <ul>
                    <li>
                      <p>Some songs are given a title. Treat these as stage directions.</p>
                    </li>
                  </ul>
                </li>
                <li>
                  <h5>Indents and Right Alignments</h5>
                  <ul>
                    <li>
                      <p>Most songs are styled with indents and are easy to style.</p>
                    </li>
                    <li>
                      <p>If there is a right-aligned line in a song, then you can guess the indent level.</p>
                    </li>
                    <li>
                      <p>See <a href="https://github.com/standardebooks/william-shakespeare_a-midsummer-nights-dream"><i>A Midsummer Night’s Dream</i></a> and <a href="https://github.com/standardebooks/william-shakespeare_the-tempest"><i>The Tempest</i></a> for song examples.</p>
                    </li>
                  </ul>
                </li>
              </ol>
            </li>
          </ol>
        </li>
        <li>
          <h3>Epilogues</h3>
          <ol>
            <li>
              <h4>Locations</h4>
              <ul>
                <li>
                  <p>Epilogues appear at the end of the whole play.</p>
                </li>
                <li>
                  <p>See <a href="https://github.com/standardebooks/william-shakespeare_henry-v"><i>Henry V</i></a>.</p>
                </li>
              </ul>
            </li>
            <li>
              <h4>Structure</h4>
              <ul>
                <li>
                  <p>Epilogues follow a verse structure. See <a href="https://standardebooks.org/manual/1.1.1/7-high-level-structural-patterns#7.5">section 7.5 in the manual</a>.</p>
                </li>
              </ul>
            </li>
            <li>
              <h4>Chorus or No Chorus</h4>
              <ul>
                <li>
                  <p>The decision on whether or not the Epilogue contains a chorus persona and a stage direction is dependent on the page scans.</p>
                </li>
              </ul>
            </li>
        </li>
        <li>
          <h3>Content.opf</h3>
          <p>Because their are countless editions of Shakespeare’s plays our productions note the Victoria Edition at the end of our long descriptions. Add “This Standard Ebooks production is based on William George Clark and William Aldis Wright’s 1887 Victoria edition, which is taken from the Globe edition.”</p>
        </li>
      </ol>
    </section>
  </article>
</main>
<?= Template::Footer() ?>
