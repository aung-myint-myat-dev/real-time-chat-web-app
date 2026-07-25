<template>
  <GuestLayout>
     <HeroSection id="home"/>
    <TechStackSection id="tech"/>
    <FeatureSection id="features"/>
    <ContactSection id="contact"/>
    <FooterSection/>
  </GuestLayout>
</template>

<script setup>
import GuestLayout from '../layouts/GuestLayout.vue';
import ContactSection from '../sections/ContactSection.vue';
import FeatureSection from '../sections/FeatureSection.vue';
import FooterSection from '../sections/FooterSection.vue';
import HeroSection from '../sections/HeroSection.vue';
import TechStackSection from '../sections/TechStackSection.vue';

</script>