        <ul class="list list-unstyled list-scrollbar" data-wb="role=tree&form=admin&item=interface&field=menu&children=false">
            <li class="list-item" data-wb-where='"{{_lvl}}" = "0"' data-wb-allow="{{data.allow}}" data-wb-disallow="{{data.disallow}}">
              <meta data-wb="role=variable&var=label" data-wb-if='"{{data.lang.{{_env.lang}}.label}}">""' value="{{data.lang.{{_env.lang}}.label}}" else="{{name}}">
              <p class="list-title text-uppercase">{{_var.label}}</p>
              <ul class="list-unstyled" data-wb="role=foreach&from=children">
                  <li data-wb-where='children="" AND active = "on"' data-wb-allow="{{data.allow}}" data-wb-disallow="{{data.disallow}}">
                    <meta data-wb="role=variable&var=label" data-wb-if='"{{data.lang.{{_env.lang}}.label}}">""' value="{{data.lang.{{_env.lang}}.label}}" else="{{name}}">
                    <a  href="{{data.link}}" target="{{data.target}}" class="list-link" data-wb-where='"{{substr({{data.target}},0,1)}}"!="#"'>
                      <i class="{{data.icon}}" aria-hidden="true" data-wb-where='"{{data.icon}}">""'></i>
                      {{_var.label}}
                    </a>
                    <a href="#" data-wb="role=ajax&url={{data.link}}&html={{data.target}}" class="list-link" data-wb-where='"{{substr({{data.target}},0,1)}}" = "#"'>
                      <i class="{{data.icon}}" aria-hidden="true" data-wb-where='"{{data.icon}}">""'></i>
                      {{_var.label}}
                    </a>
                  </li>
                  <li data-wb-where='children>"" AND active = "on"' data-wb-allow="{{data.allow}}" data-wb-disallow="{{data.disallow}}">
                    <a href="#" class="list-link link-arrow down">
                      <meta data-wb="role=variable&var=label" data-wb-if='"{{data.lang.{{_env.lang}}.label}}">""' value="{{data.lang.{{_env.lang}}.label}}" else="{{name}}">
                      <i class="{{data.icon}}" aria-hidden="true" data-wb-where='"{{data.icon}}">""'></i>
                      {{_var.label}}
                    </a>
                    <ul class="list-unstyled list-hidden" data-wb="role=tree&from=children">
                        <li data-wb-where='"{{active}}" = "on"' data-wb-allow="{{data.allow}}" data-wb-disallow="{{data.disallow}}">
                          <meta data-wb="role=variable&var=label" data-wb-if='"{{data.lang.{{_env.lang}}.label}}">""' value="{{data.lang.{{_env.lang}}.label}}" else="{{name}}">
                          <a  href="{{data.link}}" target="{{data.target}}" class="list-link" data-wb-where='"{{substr({{data.target}},0,1)}}"!="#"'>
                            <i class="{{data.icon}}" aria-hidden="true" data-wb-where='"{{data.icon}}">""'></i>
                            {{_var.label}}
                          </a>
                          <a href="#" data-wb="role=ajax&url={{data.link}}&html={{data.target}}" class="list-link" data-wb-where='"{{substr({{data.target}},0,1)}}"="#"'>
                            <i class="{{data.icon}}" aria-hidden="true" data-wb-where='"{{data.icon}}">""'></i>
                            {{_var.label}}
                          </a>
                        </li>
                    </ul>
                  </li>
              </ul>
            </li>

        </ul>
