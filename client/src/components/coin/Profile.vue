<template>
  <v-row>
    <v-col cols="7">
      <h2>About {{ profile.name }}</h2>
      <p class="mt-2">{{ profile.description }}</p>
      <div>
        <h2>Links</h2>
        <div class="d-flex flex-wrap space-between pt-3">
          <v-btn
            small
            outlined
            :href="link.link"
            target="_blank"
            v-for="(link, index) in links"
            :key="index"
            color="text"
            class="mb-3 mr-3"
          >
            <v-icon small>{{ link.icon }}</v-icon>
            <span class="ml-1">{{ link.title }}</span>
          </v-btn>
        </div>
      </div>
    </v-col>
    <v-col>
      <h2>Technical details</h2>
      <v-simple-table>
        <template v-slot:default>
          <tbody>
            <tr>
              <td>Token Type</td>
              <td v-if="profile.type !== null">
                {{ profile.type }}
              </td>
              <td v-else>—</td>
            </tr>
            <tr>
              <td>Genesis Date</td>
              <td v-if="profile.genesisDate !== null">
                {{ profile.genesisDate | prettifyDate }}
              </td>
              <td v-else>—</td>
            </tr>
            <tr>
              <td>Hashing Algorithm</td>
              <td v-if="profile.hashingAlgorithm !== null">
                {{ profile.hashingAlgorithm }}
              </td>
              <td v-else>—</td>
            </tr>
            <tr>
              <td>Consensus Mechanism</td>
              <td v-if="profile.consensusMechanism !== null">
                {{ profile.consensusMechanism }}
              </td>
              <td v-else>—</td>
            </tr>
          </tbody>
        </template>
      </v-simple-table>
    </v-col>
  </v-row>
</template>

<script>
import {GET_PROFILE} from '../../store/coin/types';
import {mapGetters} from 'vuex';

export default {
  name: 'Profile',

  data() {
    return {
      linkIconMapper: {
        twitter: 'mdi-twitter',
        telegram: 'mdi-telegram',
        reddit: 'mdi-reddit',
        github: 'mdi-github',
        website: 'mdi-web',
        whitepaper: 'mdi-file-document',
        explorer: 'mdi-compass',
      },
    };
  },

  computed: {
    ...mapGetters('coin', {
      profile: GET_PROFILE,
    }),

    links() {
      return this.profile.links.map(link => {
        let icon;
        if (link.type.toLowerCase() in this.linkIconMapper) {
          icon = this.linkIconMapper[link.type.toLowerCase()];
        } else {
          icon = this.linkIconMapper.website;
        }

        return {
          title: link.type,
          link: link.link,
          icon: icon,
        };
      });
    },
  },
};
</script>

<style scoped></style>
